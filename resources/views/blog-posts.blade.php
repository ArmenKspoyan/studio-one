<x-app-layout>
    <x-edit-modal id="edit-modal" title="Edit">
    </x-edit-modal>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-12 sm:p-12 bg-white shadow sm:rounded-lg">
                <div>
                    <section>
                        <x-primary-button class="add-blog-post">{{ __('Create New Posts') }}</x-primary-button>
                        <br/>
                        <br/>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __("Blog Post Lists") }}
                            </h2>
                        </header>

                        <table class="table blog-post">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Content</th>
                                <th scope="col">Created User Name</th>
                                <th scope="col">Edit</th>
                                <th scope="col">Delete</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($blogPosts as $blogPost)
                                <tr>
                                    <th scope="row">{{$blogPost->id}}</th>
                                    <td>{{$blogPost->title}}</td>
                                    <td>{{$blogPost->content}}</td>
                                    <td>{{$blogPost->user->name}}</td>
                                    <td>
                                        <button data-bs-toggle="modal"
                                                data-bs-target="#edit-modal" class="btn btn-success edit-blog-post"
                                                blog-post-id="{{ $blogPost->id }}"
                                                @cannot('update', $blogPost) disabled
                                            @endcannot
                                        >Edit
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" blog-post-id="{{ $blogPost->id }}"
                                                class="btn btn-danger delete-button"
                                                @cannot('delete', $blogPost) disabled
                                            @endcannot>
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                            {!! $blogPosts->links('pagination::bootstrap-4') !!}
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('click', async function (event) {
        if (!event.target.classList.contains('add-blog-post')) {
            return;
        }

        try {
            const response = await fetch('/add-blog-post', {
                method: 'GET'
            });
            window.location.href = window.location.origin + '/add-blog-post';
        } catch (error) {
            console.error('Server error:', error);
        }
    });

    $(document).ready(function () {
        function fetchBlogPost(id) {
            return fetch(`/get-blog-post?id=${id}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            }).then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.message);
                }
                return response.json();
            });
        }

        function populateModal(data) {
            const modalBody = $('#edit-modal .modal-body');
            modalBody.empty();

            modalBody.append(`
            <label for="title" class="form-label">Title</label>
            <input id="title" value="${data.data.title}" name="title" type="text" class="mt-1 block w-full form-control" required autofocus />
        `);

            modalBody.append(`
            <label for="blog-post" class="form-label">Content</label>
            <textarea id="blog-post" rows="8" class="form-control" name="blog-post" required>${data.data.content}</textarea>
        `);

            $('#save-changes').data('blog-post-id', data.data.id);
            $('#edit-modal').modal('show');
        }

        function updateBlogPost(id, content, title) {
            return fetch('update-blog-post', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ id, content, title }),
            }).then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            });
        }

        $('.edit-blog-post').click(function () {
            const id = $(this).attr('blog-post-id');

            fetchBlogPost(id)
                .then(populateModal)
                .catch(error => {
                    console.error('Error fetching blog post:', error);
                });
        });


        $(document).on('click', '#save-changes', function () {
            const content = $('#edit-modal textarea[name="blog-post"]').val();
            const title = $('#edit-modal input[name="title"]').val();
            const id = $(this).data('blog-post-id');

            updateBlogPost(id, content, title)
                .then(data => {
                    if (data.success) {
                        $('#edit-modal').modal('hide');
                        location.reload();
                    } else {
                        console.error('Failed to update the post:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error updating blog post:', error);
                });
        });
    });



    document.addEventListener('click', async function (event) {
        if (event.target.classList.contains('delete-button')) {
            const id = event.target.getAttribute('blog-post-id');
            const url = `/delete-blog-post/${id}`;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const result = await response.json();

                if (result.success) {
                    event.target.closest('.blog-post').remove();

                } else {
                    console.error('You dont have a permission to perform this action!');
                }
            } catch (error) {
                console.error('Network or server error', error);
            }
        }
    });

</script>

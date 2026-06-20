@extends('layouts.backend')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Comments</h1>
        <p class="text-muted mb-0">Moderate all comments</p>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Author</th>
                    <th>Comment</th>
                    <th>Place</th>
                    <th width="100">Status</th>
                    <th width="150">Date</th>
                    <th width="180">Actions</th>
                </tr>
            </thead>
            <tbody>

            @foreach($comments as $comment)
                <tr>
                    <td class="fw-semibold">{{ $comment->name }}</td>

                    <td>
                        <div style="max-width:300px; font-size:13px;">
                            {{ Str::limit($comment->body, 80) }}
                        </div>
                        @if($comment->parent_id)
                            <span class="badge bg-light text-muted border" style="font-size:11px;">
                                ↩ Reply
                            </span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('places.show', $comment->place->slug) }}"
                           target="_blank"
                           class="text-decoration-none small"
                           style="color:#2176ae;">
                            {{ $comment->place->title }}
                        </a>
                    </td>

                    <td>
                        @if($comment->is_approved)
                            <span class="badge bg-success">Approved</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>

                    <td class="text-muted small">
                        {{ $comment->created_at->diffForHumans() }}
                    </td>

                    <td>
                        <div class="d-flex align-items-center gap-1">
                            @if(!$comment->is_approved)
                                <form action="{{ route('backend.comments.approve', $comment) }}"
                                    method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-outline-success" style="width:100px;">Approve</button>
                                </form>
                            @else
                                <form action="{{ route('backend.comments.disapprove', $comment) }}"
                                    method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-outline-warning" style="width:100px;">Disapprove</button>
                                </form>
                            @endif

                            <form action="{{ route('backend.comments.destroy', $comment) }}"
                                method="POST"
                                class="d-inline-block ms-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Delete this comment? This cannot be undone.')"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Delete comment">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach

            @if($comments->isEmpty())
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        No comments yet
                    </td>
                </tr>
            @endif

            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $comments->links('pagination::bootstrap-5') }}
</div>

@endsection
@extends('layouts.app')
@section('title', 'Users')
@section('content')
<div class="dashboard-layout">
    <aside class="sidebar"><a class="sidebar-brand" href="{{ route('home') }}">PaxiliArt</a><nav class="sidebar-nav"><a href="{{ route('admin.dashboard') }}">▦ &nbsp; Dashboard</a><a href="{{ route('admin.orders') }}">▤ &nbsp; Orders</a><a class="active" href="{{ route('admin.users') }}">♙ &nbsp; Users</a><a href="{{ route('home') }}">⌂ &nbsp; View marketplace</a></nav><div class="sidebar-bottom">Administrator</div></aside>
    <section class="dashboard-main">
        <div class="dashboard-top"><div><span class="eyebrow">Our creative community</span><h1>Users</h1></div><a class="button button-outline button-small" href="{{ route('admin.dashboard') }}">← Dashboard</a></div>
        <div class="table-wrap"><table class="data-table"><thead><tr><th>Member</th><th>Email</th><th>Role</th><th>Artworks</th><th>Orders</th><th>Joined</th></tr></thead><tbody>
            @forelse ($users as $user)
                <tr><td><strong>{{ $user->name }}</strong><br><span class="muted">@{{ $user->username }}</span></td><td>{{ $user->email }}</td><td><span class="status {{ $user->role === 'customer' ? 'status-pending' : '' }}">{{ ucfirst($user->role) }}</span></td><td>{{ $user->artworks_count }}</td><td>{{ $user->orders_count }}</td><td>{{ $user->created_at->format('M j, Y') }}</td></tr>
            @empty
                <tr><td colspan="6" class="muted">No registered members yet.</td></tr>
            @endforelse
        </tbody></table></div>
        {{ $users->links() }}
    </section>
</div>
@endsection

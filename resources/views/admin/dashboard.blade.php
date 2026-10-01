@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-header">
        <h1>Dashboard</h1>
    </div>

    <div class="stats">
        <div class="stat-card">
            <span class="stat-label">Users</span>
            <span class="stat-value">{{ $userCount }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-label">Products</span>
            <span class="stat-value">{{ $productCount }}</span>
        </div>
    </div>
@endsection

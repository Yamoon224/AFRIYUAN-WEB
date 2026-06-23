@extends('layouts.app')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-3">
            <p class="text-sm text-gray-500">{{ $notifications->total() }} notification(s)</p>
            @if($unreadCount > 0)
            <span class="text-xs bg-primary-100 text-primary-700 font-semibold px-2 py-0.5 rounded-full">
                {{ $unreadCount }} non lue(s)
            </span>
            @endif
        </div>
        @if($unreadCount > 0)
        <form method="POST" action="{{ route('notifications.markAllRead') }}">
            @csrf
            <button type="submit" class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                Tout marquer comme lu
            </button>
        </form>
        @endif
    </div>

    @if($notifications->isEmpty())
    <div class="card text-center py-16">
        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
        </div>
        <p class="text-gray-700 font-medium">Aucune notification</p>
        <p class="text-sm text-gray-400">Vous êtes à jour !</p>
    </div>
    @else
    <div class="space-y-2">
        @foreach($notifications as $notif)
        <div class="card {{ !$notif->read_at ? 'border-primary-200 bg-primary-50/20' : '' }} transition-colors">
            <div class="flex items-start gap-4">
                {{-- Icon --}}
                <div class="w-10 h-10 rounded-xl shrink-0 flex items-center justify-center
                    @switch($notif->type)
                        @case('transfer_completed') bg-green-100 text-green-600 @break
                        @case('transfer_failed') bg-red-100 text-red-600 @break
                        @case('kyc_approved') bg-green-100 text-green-600 @break
                        @case('kyc_rejected') bg-red-100 text-red-600 @break
                        @case('kyc_required') bg-amber-100 text-amber-600 @break
                        @default bg-gray-100 text-gray-500
                    @endswitch">
                    @switch($notif->type)
                        @case('transfer_completed')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @break
                        @case('transfer_failed')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @break
                        @default
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @endswitch
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $notif->data['title'] ?? ucfirst(str_replace('_', ' ', $notif->type)) }}
                            </p>
                            @if(isset($notif->data['body']))
                            <p class="text-sm text-gray-500 mt-0.5">{{ $notif->data['body'] }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            @if(!$notif->read_at)
                            <span class="w-2 h-2 rounded-full bg-primary-500"></span>
                            @endif
                            <p class="text-xs text-gray-400 whitespace-nowrap">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mt-2">
                        @if(isset($notif->data['transaction_id']))
                        <a href="{{ route('transfers.show', $notif->data['transaction_id']) }}"
                           class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                            Voir le transfert →
                        </a>
                        @endif
                        @if(!$notif->read_at)
                        <form method="POST" action="{{ route('notifications.markRead', $notif->id) }}">
                            @csrf
                            <button type="submit" class="text-xs text-gray-400 hover:text-gray-600">Marquer comme lu</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($notifications->hasPages())
    <div class="mt-5">{{ $notifications->links() }}</div>
    @endif
    @endif
</div>
@endsection

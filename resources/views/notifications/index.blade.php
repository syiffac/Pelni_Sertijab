<!-- filepath: resources/views/notifications/index.blade.php -->
@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Notifikasi</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary" id="markAllAsReadBtn">
                            <i class="bi bi-check-all me-1"></i> Tandai semua telah dibaca
                        </button>
                        <button type="button" class="btn btn-outline-danger" id="clearAllNotificationsBtn">
                            <i class="bi bi-trash me-1"></i> Hapus semua
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($notifications->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-bell text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Tidak ada notifikasi</h5>
                            <p class="text-muted">Semua notifikasi akan muncul di sini</p>
                        </div>
                    @else
                        <div class="notification-list">
                            @foreach($notifications as $notification)
                                <div class="notification-item-full {{ !$notification->read ? 'unread' : '' }}" id="notification-{{ $notification->id }}">
                                    @if(!$notification->read)
                                        <span class="unread-indicator"></span>
                                    @endif
                                    <div class="notification-content">
                                        <div class="notification-icon {{ $notification->type }}">
                                            <i class="bi bi-{{ $notification->icon }}"></i>
                                        </div>
                                        <div class="notification-text">
                                            <h6 class="notification-title">{{ $notification->title }}</h6>
                                            <p class="notification-message">{{ $notification->message }}</p>
                                            <div class="notification-meta">
                                                <span class="notification-time">{{ $notification->created_at->format('d M Y, H:i') }}</span>
                                            </div>
                                            <div class="notification-actions">
                                                @if($notification->link)
                                                    <a href="{{ $notification->link }}" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye"></i> Lihat detail
                                                    </a>
                                                @endif
                                                
                                                @if(!$notification->read)
                                                    <button type="button" class="btn btn-sm btn-outline-secondary mark-as-read-btn" data-id="{{ $notification->id }}">
                                                        <i class="bi bi-check2"></i> Tandai telah dibaca
                                                    </button>
                                                @endif
                                                
                                                <button type="button" class="btn btn-sm btn-outline-danger notification-delete-btn" data-id="{{ $notification->id }}">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.notification-item-full {
    padding: 16px;
    border-bottom: 1px solid rgba(0,0,0,0.1);
    position: relative;
    transition: all 0.3s ease;
}

.notification-item-full:last-child {
    border-bottom: none;
}

.notification-item-full:hover {
    background-color: rgba(0,0,0,0.02);
}

.notification-item-full.unread {
    background-color: rgba(13, 110, 253, 0.05);
}

.notification-item-full.unread .notification-title {
    font-weight: 600;
}

.notification-item-full .unread-indicator {
    position: absolute;
    top: 24px;
    left: 8px;
    width: 8px;
    height: 8px;
    background-color: #0d6efd;
    border-radius: 50%;
}

.notification-content {
    display: flex;
}

.notification-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 16px;
    flex-shrink: 0;
    font-size: 1.5rem;
}

.notification-icon.info {
    background-color: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}

.notification-icon.warning {
    background-color: rgba(255, 193, 7, 0.1);
    color: #ffc107;
}

.notification-icon.success {
    background-color: rgba(25, 135, 84, 0.1);
    color: #198754;
}

.notification-icon.danger {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.notification-text {
    flex: 1;
}

.notification-title {
    margin: 0 0 8px 0;
    font-size: 16px;
    line-height: 1.3;
}

.notification-message {
    margin: 0 0 12px 0;
    font-size: 14px;
    color: #6c757d;
    line-height: 1.5;
}

.notification-meta {
    display: flex;
    align-items: center;
    margin-bottom: 16px;
}

.notification-time {
    font-size: 13px;
    color: #adb5bd;
}

.notification-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

@media (max-width: 576px) {
    .notification-actions {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .notification-actions .btn {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark as read functionality
    document.querySelectorAll('.mark-as-read-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const notificationId = this.dataset.id;
            markAsRead(notificationId);
        });
    });

    // Delete notification functionality
    document.querySelectorAll('.notification-delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const notificationId = this.dataset.id;
            deleteNotification(notificationId);
        });
    });

    // Mark all as read
    document.getElementById('markAllAsReadBtn').addEventListener('click', function() {
        fetch('{{ route("notifications.mark-all-as-read") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI
                document.querySelectorAll('.notification-item-full.unread').forEach(item => {
                    item.classList.remove('unread');
                    const unreadIndicator = item.querySelector('.unread-indicator');
                    if (unreadIndicator) unreadIndicator.remove();
                    
                    const markAsReadBtn = item.querySelector('.mark-as-read-btn');
                    if (markAsReadBtn) markAsReadBtn.closest('.btn').remove();
                });
                
                // Show success message
                showAlert('Semua notifikasi ditandai sebagai telah dibaca', 'success');
            }
        })
        .catch(error => {
            console.error('Error marking all as read:', error);
            showAlert('Terjadi kesalahan saat menandai notifikasi', 'danger');
        });
    });

    // Clear all notifications
    document.getElementById('clearAllNotificationsBtn').addEventListener('click', function() {
        if (!confirm('Apakah Anda yakin ingin menghapus semua notifikasi?')) return;
        
        fetch('{{ route("notifications.destroy-all") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload page
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error clearing all notifications:', error);
            showAlert('Terjadi kesalahan saat menghapus notifikasi', 'danger');
        });
    });

    // Mark notification as read
    function markAsRead(notificationId) {
        fetch(`{{ url('/notifications') }}/${notificationId}/mark-as-read`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI
                const notificationItem = document.getElementById(`notification-${notificationId}`);
                if (notificationItem) {
                    notificationItem.classList.remove('unread');
                    const unreadIndicator = notificationItem.querySelector('.unread-indicator');
                    if (unreadIndicator) unreadIndicator.remove();
                    
                    const markAsReadBtn = notificationItem.querySelector('.mark-as-read-btn');
                    if (markAsReadBtn) markAsReadBtn.closest('.btn').remove();
                }
                
                // Show success message
                showAlert('Notifikasi ditandai sebagai telah dibaca', 'success');
            }
        })
        .catch(error => {
            console.error('Error marking as read:', error);
            showAlert('Terjadi kesalahan saat menandai notifikasi', 'danger');
        });
    }

    // Delete notification
    function deleteNotification(notificationId) {
        fetch(`{{ url('/notifications') }}/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI with animation
                const notificationItem = document.getElementById(`notification-${notificationId}`);
                if (notificationItem) {
                    notificationItem.style.height = notificationItem.offsetHeight + 'px';
                    notificationItem.style.opacity = '0';
                    notificationItem.style.transform = 'translateX(100%)';
                    
                    setTimeout(() => {
                        notificationItem.style.height = '0';
                        notificationItem.style.padding = '0';
                        notificationItem.style.margin = '0';
                        notificationItem.style.borderWidth = '0';
                        
                        setTimeout(() => {
                            notificationItem.remove();
                            
                            // If no more notifications, reload page
                            if (document.querySelectorAll('.notification-item-full').length === 0) {
                                location.reload();
                            }
                        }, 300);
                    }, 300);
                }
                
                // Show success message
                showAlert('Notifikasi berhasil dihapus', 'success');
            }
        })
        .catch(error => {
            console.error('Error deleting notification:', error);
            showAlert('Terjadi kesalahan saat menghapus notifikasi', 'danger');
        });
    }

    // Show alert message
    function showAlert(message, type) {
        const alertPlaceholder = document.createElement('div');
        alertPlaceholder.className = 'position-fixed bottom-0 end-0 p-3';
        alertPlaceholder.style.zIndex = '5';
        document.body.appendChild(alertPlaceholder);
        
        const wrapper = document.createElement('div');
        wrapper.innerHTML = `
            <div class="alert alert-${type} alert-dismissible" role="alert">
                <div>${message}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        alertPlaceholder.append(wrapper);
        
        // Auto close after 5 seconds
        setTimeout(() => {
            const alert = wrapper.querySelector('.alert');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    }
});
</script>
@endsection
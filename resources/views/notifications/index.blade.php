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
    // Delete notification functionality
    document.querySelectorAll('.notification-delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const notificationId = this.dataset.id;
            if (!confirm('Hapus notifikasi ini?')) return;
            deleteNotification(notificationId);
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
                showAlert('Semua notifikasi berhasil dihapus', 'success');
                // Reload page after short delay
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showAlert('Gagal menghapus semua notifikasi', 'danger');
            }
        })
        .catch(error => {
            console.error('Error clearing all notifications:', error);
            showAlert('Terjadi kesalahan saat menghapus notifikasi', 'danger');
        });
    });

    // Delete individual notification
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
                    // Set fixed height untuk smooth animation
                    notificationItem.style.height = notificationItem.offsetHeight + 'px';
                    notificationItem.style.overflow = 'hidden';
                    notificationItem.style.transition = 'all 0.3s ease';
                    
                    // Start fade out
                    setTimeout(() => {
                        notificationItem.style.opacity = '0';
                        notificationItem.style.transform = 'translateX(100%)';
                        notificationItem.style.height = '0';
                        notificationItem.style.padding = '0';
                        notificationItem.style.margin = '0';
                        notificationItem.style.borderWidth = '0';
                    }, 10);
                    
                    // Remove element after animation
                    setTimeout(() => {
                        notificationItem.remove();
                        
                        // Check if no more notifications
                        const remainingNotifications = document.querySelectorAll('.notification-item-full');
                        if (remainingNotifications.length === 0) {
                            // Show empty state
                            setTimeout(() => {
                                location.reload();
                            }, 500);
                        }
                    }, 350);
                }
                
                // Show success message
                showAlert('Notifikasi berhasil dihapus', 'success');
            } else {
                showAlert('Gagal menghapus notifikasi', 'danger');
            }
        })
        .catch(error => {
            console.error('Error deleting notification:', error);
            showAlert('Terjadi kesalahan saat menghapus notifikasi', 'danger');
        });
    }

    // Show alert message with better styling
    function showAlert(message, type) {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.custom-alert-notification');
        existingAlerts.forEach(alert => alert.remove());
        
        const alertPlaceholder = document.createElement('div');
        alertPlaceholder.className = 'custom-alert-notification position-fixed top-0 end-0 p-3';
        alertPlaceholder.style.zIndex = '9999';
        alertPlaceholder.style.marginTop = '20px';
        
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle';
        
        alertPlaceholder.innerHTML = `
            <div class="alert ${alertClass} alert-dismissible d-flex align-items-center" role="alert" style="min-width: 300px; animation: slideInFromRight 0.3s ease;">
                <i class="bi ${iconClass} me-2"></i>
                <div>${message}</div>
                <button type="button" class="btn-close ms-auto" onclick="this.closest('.custom-alert-notification').remove()"></button>
            </div>
        `;
        
        // Add CSS for animation
        if (!document.getElementById('alert-animation-styles')) {
            const style = document.createElement('style');
            style.id = 'alert-animation-styles';
            style.textContent = `
                @keyframes slideInFromRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOutToRight {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
            document.head.appendChild(style);
        }
        
        document.body.appendChild(alertPlaceholder);
        
        // Auto close after 4 seconds
        setTimeout(() => {
            if (alertPlaceholder.parentNode) {
                const alert = alertPlaceholder.querySelector('.alert');
                alert.style.animation = 'slideOutToRight 0.3s ease';
                setTimeout(() => {
                    if (alertPlaceholder.parentNode) {
                        alertPlaceholder.remove();
                    }
                }, 300);
            }
        }, 4000);
    }
});
</script>
@endsection
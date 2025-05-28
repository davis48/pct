// Admin Panel JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Enable tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Enable popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Auto-hide alerts
    setTimeout(function() {
        $('.alert-dismissible').alert('close');
    }, 5000);

    // File input preview for document uploads
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
    });

    // Confirm deletion
    $('.delete-confirm').on('click', function(e) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
            e.preventDefault();
        }
    });

    // Agent Request Processing Functions
    function initializeAgentRequestProcessing() {
        // Quick assign next request
        $('.assign-next-btn').on('click', function() {
            const button = $(this);
            button.prop('disabled', true).text('Assignation...');
            
            $.post('/agent/assign-next', {
                _token: $('meta[name="csrf-token"]').attr('content')
            })
            .done(function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    if (response.request_id) {
                        window.location.href = `/agent/requests/${response.request_id}/process`;
                    } else {
                        location.reload();
                    }
                } else {
                    showAlert('warning', response.message);
                    button.prop('disabled', false).text('Prendre la prochaine demande');
                }
            })
            .fail(function() {
                showAlert('danger', 'Erreur lors de l\'assignation de la demande');
                button.prop('disabled', false).text('Prendre la prochaine demande');
            });
        });

        // Make request table rows clickable
        $(document).on('click', '.request-table tbody tr', function(e) {
            // Don't trigger if clicking on buttons or links
            if ($(e.target).closest('button, a, .btn, input').length > 0) {
                return;
            }
            
            const requestId = $(this).data('request-id') || $(this).find('[data-request-id]').data('request-id');
            if (requestId) {
                window.location.href = `/agent/requests/${requestId}/process`;
            }
        });

        // Make request cards clickable
        $(document).on('click', '.request-card', function(e) {
            // Don't trigger if clicking on buttons or links
            if ($(e.target).closest('button, a, .btn, input').length > 0) {
                return;
            }
            
            const requestId = $(this).data('request-id');
            if (requestId) {
                window.location.href = `/agent/requests/${requestId}/process`;
            }
        });

        // Add cursor pointer to clickable elements
        $('.request-table tbody tr, .request-card').css('cursor', 'pointer');

        // Process request form
        $('#processRequestForm').on('submit', function(e) {
            const status = $('input[name="status"]:checked').val();
            const comments = $('textarea[name="admin_comments"]').val().trim();

            if (!status) {
                e.preventDefault();
                showAlert('warning', 'Veuillez sélectionner un statut pour la demande');
                return false;
            }

            if (!comments) {
                e.preventDefault();
                showAlert('warning', 'Veuillez ajouter un commentaire');
                return false;
            }

            if (status === 'rejected' && !confirm('Êtes-vous sûr de vouloir rejeter cette demande ?')) {
                e.preventDefault();
                return false;
            }

            if (status === 'approved' && !confirm('Êtes-vous sûr de vouloir approuver cette demande ?')) {
                e.preventDefault();
                return false;
            }
        });

        // Quick actions
        $('.quick-approve-btn').on('click', function(e) {
            e.stopPropagation();
            const requestId = $(this).data('request-id');
            if (confirm('Approuver cette demande ?')) {
                window.location.href = `/agent/requests/${requestId}/process`;
            }
        });

        $('.quick-reject-btn').on('click', function(e) {
            e.stopPropagation();
            const requestId = $(this).data('request-id');
            if (confirm('Rejeter cette demande ?')) {
                window.location.href = `/agent/requests/${requestId}/process`;
            }
        });

        // View request details
        $('.view-request-btn').on('click', function(e) {
            e.stopPropagation();
            const requestId = $(this).data('request-id');
            window.location.href = `/agent/requests/${requestId}`;
        });

        // Assign to me button
        $('.assign-to-me-btn').on('click', function(e) {
            e.stopPropagation();
            const requestId = $(this).data('request-id');
            const button = $(this);
            
            button.prop('disabled', true).text('Assignation...');
            
            $.post(`/agent/requests/${requestId}/assign`, {
                _token: $('meta[name="csrf-token"]').attr('content')
            })
            .done(function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    location.reload();
                } else {
                    showAlert('warning', response.message);
                    button.prop('disabled', false).text('M\'assigner');
                }
            })
            .fail(function() {
                showAlert('danger', 'Erreur lors de l\'assignation');
                button.prop('disabled', false).text('M\'assigner');
            });
        });
    }

    // Show alert function
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Remove existing alerts
        $('.alert').remove();
        
        // Add new alert at the top of content
        $('.content-wrapper, .main-content, .container-fluid').first().prepend(alertHtml);
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }

    // Initialize agent request processing if on agent pages
    if (window.location.pathname.includes('/agent/')) {
        initializeAgentRequestProcessing();
    }

    // Mobile sidebar toggle functionality
    function initializeMobileSidebar() {
        const toggleButton = document.querySelector('.navbar-toggler');
        const sidebar = document.getElementById('sidebarMenu');

        if (toggleButton && sidebar) {
            toggleButton.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 767.98 &&
                    sidebar.classList.contains('show') &&
                    !sidebar.contains(event.target) &&
                    !toggleButton.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            });

            // Close sidebar when window is resized to desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth > 767.98) {
                    sidebar.classList.remove('show');
                }
            });
        }
    }

    // Initialize mobile sidebar
    initializeMobileSidebar();
});
                $('.bulk-actions').show();
            } else {
                $('.bulk-actions').hide();
            }
        }

        // Real-time status updates
        if (window.location.pathname.includes('/agent/')) {
            setInterval(function() {
                updateRequestCounts();
            }, 30000); // Update every 30 seconds
        }

        function updateRequestCounts() {
            $.get('/agent/notifications')
            .done(function(data) {
                // Update sidebar counters
                $('.pending-count').text(data.pending_count);
                $('.assignments-count').text(data.my_assignments_count);
                
                // Update notification badges
                if (data.pending_count > 0) {
                    $('.pending-notification').show().text(data.pending_count > 9 ? '9+' : data.pending_count);
                } else {
                    $('.pending-notification').hide();
                }
            });
        }
    }

    // Show alert function
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Remove existing alerts
        $('.alert').remove();
        
        // Add new alert at the top of content
        $('.content-wrapper, .main-content, .container-fluid').first().prepend(alertHtml);
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }

    // Initialize agent request processing if on agent pages
    if (window.location.pathname.includes('/agent/')) {
        initializeAgentRequestProcessing();
    }

    // Mobile sidebar toggle functionality
    function initializeMobileSidebar() {
        const toggleButton = document.querySelector('.navbar-toggler');
        const sidebar = document.getElementById('sidebarMenu');

        if (toggleButton && sidebar) {
            toggleButton.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 767.98 &&
                    sidebar.classList.contains('show') &&
                    !sidebar.contains(event.target) &&
                    !toggleButton.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            });

            // Close sidebar when window is resized to desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth > 767.98) {
                    sidebar.classList.remove('show');
                }
            });
        }
    }

    // Initialize mobile sidebar
    initializeMobileSidebar();
});

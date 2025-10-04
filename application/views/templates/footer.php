                </div>
                <!-- Footer -->
                <footer class="bg-light text-center text-muted py-3 mt-5">
                    <div class="container">
                        <p class="mb-0">© <?= date('Y') ?> Learning Management System. All rights reserved.</p>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Course Enrollment AJAX Script -->
    <script>
    $(document).ready(function() {
        // CSRF Token (if enabled)
        var csrfName = '<?= $this->security->get_csrf_token_name() ?>';
        var csrfHash = '<?= $this->security->get_csrf_hash() ?>';
        
        // Enroll Button Click Handler
        $('.enroll-btn').on('click', function(e) {
            e.preventDefault();
            
            var btn = $(this);
            var courseId = btn.data('course-id');
            var courseTitle = btn.data('course-title');
            var courseItem = $('#course-item-' + courseId);
            
            // Disable button to prevent double-click
            btn.prop('disabled', true);
            btn.html('<i class="bi bi-hourglass-split"></i> Enrolling...');
            
            // AJAX POST request
            $.ajax({
                url: '<?= base_url("course/enroll") ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    course_id: courseId,
                    [csrfName]: csrfHash
                },
                success: function(response) {
                    // Update CSRF token
                    csrfHash = response.<?= $this->security->get_csrf_token_name() ?> || csrfHash;
                    
                    if (response.success) {
                        // Show success alert
                        showAlert('success', response.message);
                        
                        // Remove course from available list
                        courseItem.fadeOut(400, function() {
                            $(this).remove();
                            
                            // Check if no more available courses
                            if ($('.enroll-btn').length === 0) {
                                $('.card-body').html('<p class="text-muted">No available courses at this time. You\'ve enrolled in all available courses!</p>');
                            }
                        });
                        
                        // Add to enrolled courses list
                        addToEnrolledList(response.course);
                        
                        // Update enrolled count
                        var currentCount = parseInt($('#enrolled-count').text());
                        $('#enrolled-count').text(currentCount + 1);
                        
                    } else {
                        // Show error alert
                        showAlert('danger', response.message);
                        
                        // Re-enable button
                        btn.prop('disabled', false);
                        btn.html('<i class="bi bi-plus-circle"></i> Enroll');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Enrollment Error:', error);
                    showAlert('danger', 'An error occurred. Please try again.');
                    
                    // Re-enable button
                    btn.prop('disabled', false);
                    btn.html('<i class="bi bi-plus-circle"></i> Enroll');
                }
            });
        });
        
        // Function to show Bootstrap alert
        function showAlert(type, message) {
            var alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            // Insert alert at the top of the page
            $('.container-fluid.p-4').prepend(alertHtml);
            
            // Auto-dismiss after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut(400, function() {
                    $(this).remove();
                });
            }, 5000);
        }
        
        // Function to add course to enrolled list
        function addToEnrolledList(course) {
            // Check if "no courses" message exists and remove it
            $('#no-enrolled-courses').remove();
            
            // Check if enrolled list exists
            if ($('#enrolled-courses-list').length === 0) {
                $('#enrolled-courses-section').html('<div class="list-group" id="enrolled-courses-list"></div>');
            }
            
            // Create new enrolled course item
            var enrolledHtml = `
                <div class="list-group-item list-group-item-action" data-course-id="${course.id}">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">${course.title}</h5>
                        <small class="text-success">
                            <i class="bi bi-check-circle-fill"></i> Enrolled
                        </small>
                    </div>
                    <p class="mb-1">Course content will be available soon.</p>
                    <small class="text-muted">
                        <i class="bi bi-person"></i> ${course.instructor_name} | 
                        <i class="bi bi-calendar"></i> Enrolled on ${course.enrolled_at}
                    </small>
                </div>
            `;
            
            // Prepend to enrolled list with animation
            $(enrolledHtml).hide().prependTo('#enrolled-courses-list').fadeIn(400);
        }
    });
    </script>
</body>
</html>
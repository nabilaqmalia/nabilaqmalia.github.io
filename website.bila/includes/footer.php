    <footer class="footer mt-5 py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center">
                    <div class="social-icons">
                        <a href="https://www.instagram.com/nabilaaqma_?igsh=YncycTJjOTh4ZGc=" target="_blank" class="social-icon">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://wa.me/+6285608644366" target="_blank" class="social-icon">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                    <p class="mt-3 text-muted">© 2024 Photo Gallery. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentIndex = 0;
            const images = document.querySelectorAll('.card-img-top');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            const imageModal = document.getElementById('imageModal');
            const prevButton = document.querySelector('.prev');
            const nextButton = document.querySelector('.next');
            let modalInstance = null;

            // Fungsi untuk membuka modal
            window.openImageModal = function(imageSrc, title) {
                if (modalInstance) {
                    modalInstance.dispose();
                }
                
                modalImage.src = imageSrc;
                modalTitle.textContent = title;
                currentIndex = Array.from(images).findIndex(img => img.src === imageSrc);
                
                modalInstance = new bootstrap.Modal(imageModal, {
                    keyboard: true,
                    focus: true
                });
                modalInstance.show();
                
                // Pastikan tombol navigasi terlihat
                setTimeout(resetNavigationControls, 100);
            }

            function resetNavigationControls() {
                [prevButton, nextButton].forEach(button => {
                    Object.assign(button.style, {
                        display: 'block',
                        visibility: 'visible',
                        opacity: '1',
                        zIndex: '9999',
                        position: 'fixed',
                        pointerEvents: 'auto'
                    });
                });
            }

            function changeSlide(direction) {
                currentIndex = currentIndex + direction;
                
                if (currentIndex >= images.length) currentIndex = 0;
                if (currentIndex < 0) currentIndex = images.length - 1;
                
                const fadeOut = () => {
                    modalImage.style.opacity = '0';
                    setTimeout(() => {
                        modalImage.src = images[currentIndex].src;
                        modalTitle.textContent = images[currentIndex].alt;
                        modalImage.style.opacity = '1';
                        resetNavigationControls();
                    }, 200);
                };

                fadeOut();
            }

            // Event Handlers
            const handlePrevClick = (e) => {
                e.preventDefault();
                e.stopPropagation();
                changeSlide(-1);
            };

            const handleNextClick = (e) => {
                e.preventDefault();
                e.stopPropagation();
                changeSlide(1);
            };

            // Remove old event listeners
            prevButton.replaceWith(prevButton.cloneNode(true));
            nextButton.replaceWith(nextButton.cloneNode(true));

            // Add new event listeners
            document.querySelector('.prev').addEventListener('click', handlePrevClick, true);
            document.querySelector('.next').addEventListener('click', handleNextClick, true);

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (imageModal.classList.contains('show')) {
                    if (e.key === 'ArrowLeft') {
                        e.preventDefault();
                        changeSlide(-1);
                    }
                    if (e.key === 'ArrowRight') {
                        e.preventDefault();
                        changeSlide(1);
                    }
                }
            });

            // Modal events
            imageModal.addEventListener('shown.bs.modal', resetNavigationControls);
            imageModal.addEventListener('hidden.bs.modal', () => {
                if (modalInstance) {
                    modalInstance.dispose();
                    modalInstance = null;
                }
            });

            // Handle fullscreen changes
            document.addEventListener('fullscreenchange', resetNavigationControls);
            document.addEventListener('webkitfullscreenchange', resetNavigationControls);
            document.addEventListener('mozfullscreenchange', resetNavigationControls);
            document.addEventListener('MSFullscreenChange', resetNavigationControls);
        });

        function searchPhotos(query) {
            query = query.toLowerCase();
            const cards = document.querySelectorAll('.col-md-4');
            
            cards.forEach(card => {
                const title = card.querySelector('.card-title').textContent.toLowerCase();
                const description = card.querySelector('.card-text').textContent.toLowerCase();
                
                if (title.includes(query) || description.includes(query)) {
                    card.style.display = '';
                    // Tambahkan animasi fade in
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.style.opacity = '1';
                    }, 50);
                } else {
                    // Animasi fade out sebelum hide
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
            
            // Tampilkan pesan jika tidak ada hasil
            const noResults = document.getElementById('noResults');
            const visibleCards = document.querySelectorAll('.col-md-4[style=""]').length;
            
            if (visibleCards === 0 && query !== '') {
                if (!noResults) {
                    const message = document.createElement('div');
                    message.id = 'noResults';
                    message.className = 'col-12 text-center mt-4';
                    message.innerHTML = `
                        <div class="no-results">
                            <i class="fas fa-search mb-3"></i>
                            <h3>No photos found</h3>
                            <p>Try different keywords or remove search filters</p>
                        </div>
                    `;
                    document.querySelector('.row').appendChild(message);
                }
            } else if (noResults) {
                noResults.remove();
            }
        }

        function clearSearch() {
            document.getElementById('searchInput').value = '';
            searchPhotos('');
            document.getElementById('searchInput').focus();
        }
        
        // Show/hide clear button based on input
        document.getElementById('searchInput').addEventListener('input', function() {
            const clearButton = document.querySelector('.clear-search');
            clearButton.style.display = this.value ? 'block' : 'none';
        });

        // Initialize Bootstrap modals
        var modals = document.querySelectorAll('.modal');
        modals.forEach(function(modal) {
            new bootstrap.Modal(modal);
        });

        // Function to close modal
        function closeModal() {
            var modals = document.querySelectorAll('.modal');
            modals.forEach(function(modal) {
                var modalInstance = bootstrap.Modal.getInstance(modal);
                if(modalInstance) {
                    modalInstance.hide();
                }
            });
        }

        // Add event listener for escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") {
                closeModal();
            }
        });

        // Add event listener for exit button
        document.querySelectorAll('.btn-exit').forEach(function(btn) {
            btn.addEventListener('click', closeModal);
        });

        // Add event listener for clicking outside modal
        document.querySelectorAll('.modal').forEach(function(modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });
        });
    </script>
</body>
</html>
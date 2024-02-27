<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card-title {
            overflow-wrap: break-word;
            color: #087cfc; /* Ubah warna judul kartu */
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #f8f9fa; /* Ubah warna latar belakang footer sesuai kebutuhan */
            text-align: center;
        }
    </style>
</head>
<body style="margin: -10px 0;">

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container">
        <!-- @include("components.guessnavbar") -->
        <!-- Tambahkan navigasi navbar di sini -->
    </div>
</nav>

<section>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form class="form-inline">
                    <input id="searchInput" class="form-control mr-sm-2 flex-grow-1" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="container mt-5">
    <div class="row" id="documentCards">
        <!-- Kartu dokumen akan ditampilkan di sini -->
    </div>
</div>

<div class="container mt-5">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-end" id="pagination">
            <!-- Tombol penomoran halaman akan ditampilkan di sini -->
        </ul>
    </nav>
</div>

<footer class="footer py-3">
    <!-- @include('components.footer') -->
    <!-- Footer konten akan ditampilkan di sini -->
</footer>

<!-- jQuery and Bootstrap JS libraries -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Data dokumen
        const documents = {!! json_encode($documents) !!};
        const numPerPage = 8;
        let currentPage = 1;

        // Fungsi untuk menampilkan kartu dokumen sesuai halaman yang dipilih
        function displayDocuments(page) {
            const startIndex = (page - 1) * numPerPage;
            const endIndex = startIndex + numPerPage;
            const paginatedDocuments = documents.slice(startIndex, endIndex);

            const documentCardsContainer = document.getElementById('documentCards');
            documentCardsContainer.innerHTML = '';

            paginatedDocuments.forEach(function(e) {
                const accessor = e.give_access_to.split(";");
                const documentTitle = e.name.length > 75 ? e.name.substring(0, 75) + "..." : e.name;
                if (accessor.includes('0')) {
                    const cardHTML = `
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h2 class="card-title">${documentTitle}</h2>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Tipe: ${e.tipe_dokumen} | User Upload: ${e.created_by.name}</p>
                                    <a href="/viewdocumentdetail/${e.id}" class="btn btn-primary">View Detail</a>
                                </div>
                            </div>
                        </div>`;
                    documentCardsContainer.innerHTML += cardHTML;
                }
            });
        }

        // Fungsi untuk menampilkan tombol-tombol penomoran halaman
        function displayPagination() {
            const totalPages = Math.ceil(documents.length / numPerPage);
            const paginationContainer = document.getElementById('pagination');
            paginationContainer.innerHTML = '';

            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement('li');
                li.classList.add('page-item');
                if (i === currentPage) {
                    li.classList.add('active');
                }
                const a = document.createElement('a');
                a.classList.add('page-link');
                a.href = '#';
                a.innerText = i;
                a.addEventListener('click', function() {
                    currentPage = i;
                    displayDocuments(currentPage);
                    updatePagination();
                });
                li.appendChild(a);
                paginationContainer.appendChild(li);
            }
        }

        // Fungsi untuk memperbarui tampilan penomoran halaman
        function updatePagination() {
            const paginationItems = document.querySelectorAll('#pagination .page-item');
            paginationItems.forEach(function(item, index) {
                if (index + 1 === currentPage) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        }

        // Pemanggilan fungsi untuk menampilkan dokumen dan penomoran halaman
        displayDocuments(currentPage);
        displayPagination();

        // Fungsi pencarian
        const searchInput = document.getElementById("searchInput");
        searchInput.addEventListener("input", function() {
            const searchQuery = searchInput.value.toLowerCase();
            const filteredDocuments = documents.filter(function(e) {
                const documentTitle = e.name.toLowerCase();
                return documentTitle.includes(searchQuery);
            });
            currentPage = 1;
            displayDocuments(currentPage);
            displayPagination();
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil elemen input pencarian
        const searchInput = document.getElementById("searchInput");

        // Tambahkan event listener untuk mengawasi perubahan input
        searchInput.addEventListener("input", function() {
            // Ambil nilai pencarian
            const searchQuery = searchInput.value.toLowerCase();

            // Ambil semua kartu dokumen
            const documentCards = document.querySelectorAll(".card");

            // Iterasi melalui setiap kartu dokumen
            documentCards.forEach(function(card) {
                // Ambil judul dokumen dari kartu
                const documentTitle = card.querySelector(".card-title").innerHTML.toLowerCase();

                // Periksa apakah judul dokumen cocok dengan kueri pencarian
                if (documentTitle.includes(searchQuery)) {
                    // Tampilkan kartu dokumen jika cocok
                    card.style.display = "block";
                } else {
                    // Sembunyikan kartu dokumen jika tidak cocok
                    card.style.display = "none";
                }
            });
        });
    });
</script>

</body>
</html>

// Script de filtrage et recherche

document.addEventListener('DOMContentLoaded', function () {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const postItems = document.querySelectorAll('.post-item');
    const searchInput = document.getElementById('searchInput');
    const articlesContainer = document.getElementById('articlesContainer');
    const noResults = document.getElementById('noResults');

    // Filtrage par catégorie
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
        const filter = btn.getAttribute('data-filter');
        
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        let visible = 0;
        postItems.forEach(item => {
            const category = item.getAttribute('data-category');
            if (filter === 'all' || category === filter) {
            item.style.display = 'block';
            visible++;
            } else {
            item.style.display = 'none';
            }
        });

        noResults.style.display = visible === 0 ? 'block' : 'none';
        });
    });

    // Recherche en temps réel
    searchInput.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase().trim();
        let visible = 0;

        postItems.forEach(item => {
        const title = item.querySelector('.card-title').textContent.toLowerCase();
        const text = item.querySelector('.card-text').textContent.toLowerCase();
        if (title.includes(query) || text.includes(query)) {
            item.style.display = 'block';
            visible++;
        } else {
            item.style.display = 'none';
        }
        });

        noResults.style.display = visible === 0 ? 'block' : 'none';
    });
});

// script pour la pagination 

document.addEventListener('DOMContentLoaded', function () {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const postItems = Array.from(document.querySelectorAll('.post-item'));
    const searchInput = document.getElementById('searchInput');
    const articlesContainer = document.getElementById('articlesContainer');
    const noResults = document.getElementById('noResults');
    const pagination = document.querySelector('.pagination');

    const itemsPerPage = 3;
    let currentPage = 1;
    let filteredItems = [...postItems];

    // Fonction : afficher les articles de la page courante
    function displayPage(page, items) {
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageItems = items.slice(start, end);

    // Masquer tous
    postItems.forEach(item => item.style.display = 'none');

    // Afficher ceux de la page
    pageItems.forEach(item => item.style.display = 'block');

    // Mettre à jour pagination
    updatePagination(page, Math.ceil(items.length / itemsPerPage));
    }

    // Fonction : créer les boutons de pagination
    function updatePagination(current, totalPages) {
    pagination.innerHTML = '';

    // Bouton Précédent
    const prevItem = document.createElement('li');
    prevItem.className = 'page-item';
    prevItem.innerHTML = `<a class="page-link" href="#">Précédent</a>`;
    if (current === 1) prevItem.classList.add('disabled');
    prevItem.addEventListener('click', (e) => {
        e.preventDefault();
        if (current > 1) goToPage(current - 1);
    });
    pagination.appendChild(prevItem);

    // Numéros de page
    for (let i = 1; i <= totalPages; i++) {
        const pageItem = document.createElement('li');
        pageItem.className = 'page-item';
        if (i === current) pageItem.classList.add('active');
        pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
        pageItem.addEventListener('click', (e) => {
        e.preventDefault();
        goToPage(i);
        });
        pagination.appendChild(pageItem);
    }

    // Bouton Suivant
    const nextItem = document.createElement('li');
    nextItem.className = 'page-item';
    nextItem.innerHTML = `<a class="page-link" href="#">Suivant</a>`;
    if (current === totalPages || totalPages === 0) nextItem.classList.add('disabled');
    nextItem.addEventListener('click', (e) => {
        e.preventDefault();
        if (current < totalPages) goToPage(current + 1);
    });
    pagination.appendChild(nextItem);
    }

    // Aller à une page
    function goToPage(page) {
    currentPage = page;
    displayPage(page, filteredItems);
    }

    // Filtrage par catégorie
    filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        const filter = btn.getAttribute('data-filter');
        
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        filteredItems = filter === 'all' 
        ? [...postItems] 
        : postItems.filter(item => item.getAttribute('data-category') === filter);

        currentPage = 1;
        displayPage(currentPage, filteredItems);
        noResults.style.display = filteredItems.length === 0 ? 'block' : 'none';
    });
    });

    // Recherche
    searchInput.addEventListener('input', () => {
    const query = searchInput.value.toLowerCase().trim();
    
    filteredItems = postItems.filter(item => {
        const title = item.querySelector('.card-title').textContent.toLowerCase();
        const text = item.querySelector('.card-text').textContent.toLowerCase();
        return title.includes(query) || text.includes(query);
    });

    currentPage = 1;
    displayPage(currentPage, filteredItems);
    noResults.style.display = filteredItems.length === 0 ? 'block' : 'none';
    });

    // Initialisation
    displayPage(1, postItems);
    noResults.style.display = 'none';
});

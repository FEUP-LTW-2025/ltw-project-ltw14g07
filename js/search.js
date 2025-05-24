const searchInput = document.getElementById('serviceSearch');
const servicesList = document.getElementById('servicesList');
const filterLanguage = document.getElementById('filterLanguage');
const filterField = document.getElementById('filterField');
const toggleFilters = document.getElementById('toggleFilters');
const advancedFilters = document.getElementById('advancedFilters');

// Debounce function to limit API calls
function debounce(func, timeout = 300) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => { func.apply(this, args); }, timeout);
    };
}

// Fetch and display search results
async function performSearch() {
    const searchTerm = searchInput.value.trim();
    
    if (searchTerm.length < 2) {
        servicesList.innerHTML = '';
        return;
    }

    try {
        const response = await fetch(`/api/search_fields_languages.php?search=${encodeURIComponent(searchTerm)}`);
        const results = await response.json();
        
        updateFiltersDropdown(filterLanguage, results.languages || []);
        updateFiltersDropdown(filterField, results.fields || []);
        
        // You can also search services here if needed
        // const servicesResponse = await fetch(`/api/search_services.php?search=${encodeURIComponent(searchTerm)}`);
        // const services = await servicesResponse.json();
        // displayServices(services);
        
    } catch (error) {
        console.error('Search failed:', error);
    }
}

// Update filter dropdowns with search results
function updateFiltersDropdown(dropdown, items) {
    dropdown.innerHTML = '<option value="">All ' + dropdown.id.replace('filter', '') + '</option>';
    items.forEach(item => {
        const option = document.createElement('option');
        option.value = item;
        option.textContent = item;
        dropdown.appendChild(option);
    });
}

// Toggle advanced filters visibility
toggleFilters.addEventListener('click', () => {
    advancedFilters.style.display = advancedFilters.style.display === 'none' ? 'block' : 'none';
});

// Initialize search with debouncing
searchInput.addEventListener('input', debounce(performSearch));

// Optional: Add event listeners for filter changes
filterLanguage.addEventListener('change', performSearch);
filterField.addEventListener('change', performSearch);
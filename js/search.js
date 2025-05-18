document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('serviceSearch');
    const searchButton = document.getElementById('searchButton');
    const servicesList = document.getElementById('servicesList');

    // Function to fetch and render services
    async function fetchServices(searchTerm = '') {
        const url = `/api/services?search=${encodeURIComponent(searchTerm)}`;
        console.log('Fetching services from URL:', url); // Debugging line to check URL
        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const services = await response.json();
            renderServices(services);
        } catch (error) {
            console.error('Error fetching services:', error);
        }
    }

    // Function to render services
    function renderServices(services) {
        servicesList.innerHTML = '';
        
        if (services.length === 0) {
            servicesList.innerHTML = '<p>No services found</p>';
            return;
        }

        services.forEach(service => {
            const serviceCard = document.createElement('div');
            serviceCard.className = 'service-card';
            serviceCard.innerHTML = `
                <h3>${service.title}</h3>
                <p>${service.description.substring(0, 100)}...</p>
                <p><strong>$${service.hourlyRate}/hr</strong></p>
                <span class="service-status">${service.status}</span>
            `;
            servicesList.appendChild(serviceCard);
        });
    }

    // Listen for search button click
    searchButton.addEventListener('click', function () {
        const searchTerm = searchInput.value.trim();
        fetchServices(searchTerm);
    });

    // Initial load: fetch all services without a search term
    fetchServices();
});

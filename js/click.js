

function addClickEvent() {
    const service_cards = document.querySelectorAll('#service-card')
    for (const card of service_cards) {
        const id = card.dataset.id
        card.addEventListener("click", function () {
            window.location.href = `/../pages/service.php?serviceID=${id}`    
        })
    }
}

addClickEvent();
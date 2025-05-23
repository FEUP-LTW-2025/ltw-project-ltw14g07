

function addClickEvent() {
    const service_cards = document.querySelectorAll('#mainPage .info-card, #manageServices .info-card, #profile .info-card')
    for (const card of service_cards) {
        const id = card.dataset.id
        card.addEventListener("click", function () {
            window.location.href = `/../pages/service.php?serviceID=${id}`    
        })
    }
}

addClickEvent();


function addClickEvent() {
    const service_cards = document.querySelectorAll('.service-card')
    for (const card of service_cards) {
        const input = card.querySelector('input')
        const id = input.value
        card.addEventListener("click", function () {
            window.location.href = `/../pages/service.php?id=${id}`    
        })
    }
}

addClickEvent();
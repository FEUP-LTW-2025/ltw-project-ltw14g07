let offset = 6

const button = document.querySelector("#loadMore")

if (button) {
    button.addEventListener("click", function () {
        console.log("tried to fetch")
        userID = document.querySelector(".listing").dataset.id

        fetch(`/../action/actionLoadMore.php?offset=${offset}&userID=${userID}`)
            .then(response => response.text()) 
            .then(service_cards => {
                if (service_cards.trim() === 'no more data') {
                    console.log("no more to show")
                    button.textContent = "No more Services"
                    button.style.backgroundColor = "white"
                    button.disabled = true
                    return;
                }
                document.querySelector(".listing ul").insertAdjacentHTML('beforeend', service_cards)
                offset+=6

                addClickEvent();
        })
})}


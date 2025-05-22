let offset = 6

document.querySelector("#loadMore").addEventListener("click", function () {
    console.log("tried to fetch")
    userID = document.querySelector(".listing").dataset.id

    fetch(`/../action/actionLoadMore.php?offset=${offset}&userID=${userID}`)
        .then(response => response.text()) 
        .then(service_cards => {
            if (service_cards.trim() === 'no more data') {
                console.log("no more to show")
                document.querySelector("#loadMore").textContent = "No more Services"
                document.querySelector("#loadMore").style.backgroundColor = "white"
                document.querySelector("#loadMore").disabled = true
                return;
            }
            document.querySelector(".listing ul").insertAdjacentHTML('beforeend', service_cards)
            offset+=6
        })
})
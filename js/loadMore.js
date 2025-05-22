let offset = 6

document.querySelector("#loadMore").addEventListener("click", function () {
    console.log("tried to fetch")
    userID = document.querySelector(".listing").dataset.id

    fetch(`/../action/actionLoadMore.php?offset=${offset}&userID=${userID}`)
        .then(response => response.text()) 
        .then(service_card => {
            document.querySelector(".listing ul").insertAdjacentHTML('beforeend', service_card)
            offset+=8
        })
})
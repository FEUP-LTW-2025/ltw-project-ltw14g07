
function addClickEvent() {
    const messages = document.querySelectorAll('#messages')
    for (const message of messages) {
        message.addEventListener("click", function () {
            message.remove()   
        })
    }

}

addClickEvent();
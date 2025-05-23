

document.querySelector(".listing button").addEventListener("click", async function () {
    event.preventDefault();

    const csrf = document.querySelector(".listing input[name='csrf']").value
    const requestID = document.querySelector(".listing input[name='requestID']").value
    const message = document.querySelector(".listing input[name='message']").value;

    const empty_msg = document.querySelector(".listing h3");
    if (empty_msg) empty_msg.remove()
    
    document.querySelector(".listing input[name='message']").value = ""
    if (message.trim() == "") return;

    console.log("clicked")

    const formData = new FormData();
    formData.append("csrf", csrf);
    formData.append("requestID", requestID);
    formData.append("message", message);

    const resp = await fetch(`/../action/actionCreateComment.php`, {
        method: 'POST',
        body : formData
    })

    if (!resp.ok) {
        const errorText = await resp.text();
        console.error("Server error:", errorText);
        return;
    }

    const comment = await resp.text();
    document.querySelector(".listing ul").insertAdjacentHTML('beforeend', comment)

})
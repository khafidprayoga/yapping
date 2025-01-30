const logoutBtn = document.getElementById("logoutBtn")
logoutBtn.addEventListener("click", function (event) {
    event.preventDefault();
    logoutBtn.disabled = true

    setTimeout(function () {
        fetch("/users/logout", {
            method: 'POST',
            credentials: 'include',
        }).then(res => window.location.replace('/signin')).finally(function () {
            logoutBtn.disabled = false
        });

    }, 1000)
})
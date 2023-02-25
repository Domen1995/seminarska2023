async function addAllowedEmail(){
    const email = document.getElementById('allowedEmail').value
    if(email.length < 6) return
    const token = document.getElementById('usersToken').content
    const response = await fetch(baseurl+"/teachers/addAllowedEmail",
                                {
                                    method: "post",
                                    //credentials: "same-origin",
                                    body: JSON.stringify(email),
                                    headers: {
                                        'Content-Type': 'application/json', //'text/plain;charset=UTF-8',
                                        /*'Accept': 'text/plain;charset=UTF-8',
                                        'url': baseurl+"/teachers/addAllowedEmail",*/
                                        "X-CSRF-Token": token
                                    }
                                })//+email)
    const text = await response.text()
    if(text == "added"){
        // add new element for this allowed email and his potential removal to DOM
        const newAllowedEmailElement = document.createElement("div")
        newAllowedEmailElement.style.display = "flex"
        newAllowedEmailElement.style.gap = ".2rem"
        const newEmailName = document.createElement("div")
        newEmailName.innerHTML = email
        //newAllowedEmailElement.innerHTML = email
        newEmailButton = document.createElement("button")
        newEmailButton.style.width = "2rem"
        newEmailButton.style.height = "2rem"
        newEmailButton.style.borderRadius = ".4rem"
        newEmailButton.setAttribute("onclick", "removeAllowedEmail(this)")
        const cancelIcon = document.createElement("i")
        cancelIcon.setAttribute("class", "material-icons")
        cancelIcon.innerHTML = "cancel"
        cancelIcon.style.color = "red"
        newEmailButton.appendChild(cancelIcon)
        newAllowedEmailElement.appendChild(newEmailName)
        newAllowedEmailElement.appendChild(newEmailButton)
        document.getElementById('emailsAllowedSoFar').appendChild(newAllowedEmailElement)
        // if the inscription saying "Any student can request enrollment" is set, remove it
        const allEmailsAllowed = document.getElementById('allEmailsAllowedInscription')
        //console.log(allEmailsAllowed)
        //if(allEmailsAllowed!=null) allEmailsAllowed.innerHTML = ""
        if(allEmailsAllowed) allEmailsAllowed.remove()
    }
    //console.log(text)
    //await fetch(BASEURL)
}

async function removeAllowedEmail(emailButton){
    const emailElement = emailButton.parentNode
    const email = emailElement.id
    const token = document.getElementById('usersToken').content
    const response = await fetch(baseurl+"/teachers/removeAllowedEmail",
                                {
                                    method: "post",
                                    //credentials: "same-origin",
                                    body: JSON.stringify(email),
                                    headers: {
                                        'Content-Type': 'application/json', //'text/plain;charset=UTF-8',
                                        /*'Accept': 'text/plain;charset=UTF-8',
                                        'url': baseurl+"/teachers/addAllowedEmail",*/
                                        "X-CSRF-Token": token
                                    }
                                })//+email)
    const text = await response.text()
    if(text == "removed"){
        emailElement.remove()
    }
    //console.log(text)
    //await fetch(BASEURL)
}

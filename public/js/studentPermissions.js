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
        const newAllowedEmailElement = document.createElement("div")
        newAllowedEmailElement.innerHTML = email
        document.getElementById('emailsAllowedSoFar').appendChild(newAllowedEmailElement)
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
    console.log(text)
    if(text == "removed"){
        emailElement.remove()
    }
    //console.log(text)
    //await fetch(BASEURL)
}

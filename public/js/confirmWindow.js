function showIpConfirmation(ip){
    createConfirmationWindow('ipQuestion', 'ipConfirmation', "Permanently add "+ip+"?", '/students/addIp?ip='+ip)
}

function show_revert_ip_checking_confirmation(presentStudentsIds){
    console.log(presentStudentsIds)
    createConfirmationWindow('revertQuestion', 'revertConfirmation', 'Sure you want to delete this presence checking?', '/teachers/revertIpChecking?presentIds='+presentStudentsIds)
}

let confirmationId
let confirmationClassName

function createConfirmationWindow(parentId, windowId, message, remainingUrl){
    //if(deletionWindowId!=false) return
    if(document.getElementById(windowId)!=null) return
    const confirmation = document.createElement("div")
    confirmation.setAttribute('id', windowId)
    //confirmation.id = "del"
    //if(document.getElementById("del")==null) return
    //deletionWindowId = confirmation.id
    confirmation.style.width = "17rem"
    confirmation.style.height = "3rem"
    confirmation.style.backgroundColor = "rgb(240, 240, 240)"
    confirmation.style.position = "absolute"
    confirmation.style.top = "10%"
    confirmation.style.left = "9rem"
    confirmation.style.zIndex = "2"
    confirmation.style.borderRadius = ".5rem"
    confirmation.style.fontFamily = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
    confirmation.style.textAlign = "center"
    confirmation.innerHTML = message
    confirmation.setAttribute('class', 'confirmationWindow')

    const confirmationButtonsWrapper = document.createElement("div")
    confirmationButtonsWrapper.style.marginLeft = "auto"
    confirmationButtonsWrapper.style.marginRight = "auto"
    confirmationButtonsWrapper.setAttribute('class', 'confirmationWindow')
    // create buttons for confirmation
    const yes = document.createElement("a")
    yes.innerHTML = "<button type='button'>Yes</button>" // make link look like a button    //yes.type = "submit"
    //yes.href = baseurl+"/users/deleteVideo?vidId="+vidId
    yes.href = baseurl+remainingUrl
    yes.style.marginRight = ".2rem"
    yes.setAttribute('class', 'confirmationWindow')
    const no = document.createElement("button")
    no.innerHTML = "No"
    no.setAttribute('class', 'confirmationWindow')
    no.style.marginLeft = ".2rem"
    no.addEventListener("click", ()=>{
        confirmation.remove()
    })
    confirmationButtonsWrapper.appendChild(yes)
    confirmationButtonsWrapper.appendChild(no)
    confirmation.appendChild(confirmationButtonsWrapper)
    justCreated = true
    //confirmation.removeEventListener('click', ()=>{})
    document.getElementById(parentId).appendChild(confirmation)
    // add global ID and class of element on which user can click without closing confirmation window
    confirmationId = windowId
    //confirmationShown = true
}

// close confirmation window on click
//let justCreated = false

addEventListener('click', (e)=>{
    //console.log(deletionWindows)
    //if(deletionWindowId==false) return
    if(justCreated) {
        justCreated = false
        return
    }
    if(document.getElementById(confirmationId)==null) return
    //if(confirmationShown == false) return
    //if(e.target.id!="del"){

    // if clicked anywhere but on window, close window
    if(e.target.className!="confirmationWindow"){
        document.getElementById(confirmationId).remove()
    }
    /*console.log(deleteWindows.pop().id)
    console.log(e.target.id)
    if(e.target!=deleteWindows.pop()){
        document.getElementById("del").remove()
    }*/
})

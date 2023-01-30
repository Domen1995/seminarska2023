//import './bootstrap';
const passwordElt = document.getElementById("password");

//document.getElementById("password").addEventListener("keyup", function(){
passwordElt.addEventListener("keyup", function(){
    const text = passwordElt.value
    if(text.length<7) passwordElt.style.backgroundColor = "rgb(255, 0, 0, .5)"
    else passwordElt.style.backgroundColor = "rgb(0, 255, 0, .4)"
})

const nicnkameElt = document.getElementById("email")
nicnkameElt.addEventListener("keyup", ()=>{
    const text = nicnkameElt.value
    if(text.length<5) nicnkameElt.style.backgroundColor = "rgb(255, 0, 0, .5)"
    else nicnkameElt.style.backgroundColor = "rgb(0, 255, 0, .4)"
})

function checkChangedInput(){
    let tekst = document.getElementById("password").value
    if(tekst.length ==0 || !isUpperCase(tekst.charAt(0))) return false
    for (var index = 1; index<tekst.length && tekst.charAt(index)!= " "; index++) {
        if(!isLowerCase(tekst.charAt(index))) {
            return false
        }
    }
    if(index>=tekst.length-1) return false
    index++
    if (!isUpperCase(tekst.charAt(index))) return false
    index++
    for(; index<tekst.length; index++){
        if(!isLowerCase(tekst.charAt(index))) return false
    }
    return true
}

function confirmDelete(vidId){
    const confirmation = document.createElement("div")
    confirmation.style.width = "17rem"
    confirmation.style.height = "4rem"
    confirmation.style.backgroundColor = "rgb(240, 240, 240)"
    confirmation.style.position = "absolute"
    confirmation.style.top = "10%"
    confirmation.style.left = "9rem"
    confirmation.style.zIndex = "2"
    confirmation.style.borderRadius = ".5rem"
    confirmation.style.fontFamily = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
    confirmation.style.textAlign = "center"
    confirmation.innerHTML = "Sure you want to delete this video?"

    const confirmationButtonsWrapper = document.createElement("div")
    confirmationButtonsWrapper.style.marginLeft = "auto"
    confirmationButtonsWrapper.style.marginRight = "auto"
    // create buttons for confirmation
    const yes = document.createElement("a")
    yes.innerHTML = "Yes"
    //yes.type = "submit"
    yes.href = "users/deleteVideo?vidId="+vidId
    const no = document.createElement("button")
    no.innerHTML = "No"
    no.addEventListener("click", ()=>{
        confirmation.remove()
    })
    confirmationButtonsWrapper.appendChild(yes)
    confirmationButtonsWrapper.appendChild(no)
    confirmation.appendChild(confirmationButtonsWrapper)
    document.getElementById("videoContainer").appendChild(confirmation)
}

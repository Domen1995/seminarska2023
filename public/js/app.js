//import './bootstrap';
const baseurl = "https://localhost/seminarska2023/public"

let passwordElt
let nicnkameElt
let emailElt

if((passwordElt = document.getElementById("password"))!=null){

    //document.getElementById("password").addEventListener("keyup", function(){
    passwordElt.addEventListener("keyup", function(){
        const text = passwordElt.value
        if(text.length<7) passwordElt.style.backgroundColor = "rgb(255, 0, 0, .5)"
        else passwordElt.style.backgroundColor = "rgb(0, 255, 0, .4)"
    })
}

if((nicnkameElt = document.getElementById("name"))!=null){
    nicnkameElt.addEventListener("keyup", ()=>{
        const text = nicnkameElt.value
        if(text.length<5) nicnkameElt.style.backgroundColor = "rgb(255, 0, 0, .5)"
        else nicnkameElt.style.backgroundColor = "rgb(0, 255, 0, .4)"
    })
}

if((emailElt = document.getElementById("email"))!=null){
    email.addEventListener("keyup", ()=>{
        const text = emailElt.value
        if(text.length<5) emailElt.style.backgroundColor = "rgb(255, 0, 0, .5)"
        else emailElt.style.backgroundColor = "rgb(0, 255, 0, .4)"
    })
}


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

//let deletionWindowId = false  // window for confirm deletion isn't shown
//let confirmationShown = false



// close deletion window on click
let justCreated = false

addEventListener('click', (e)=>{
    //console.log(deletionWindows)
    //if(deletionWindowId==false) return
    if(justCreated) {
        justCreated = false
        return
    }
    if(document.getElementById("del")==null) return
    //if(confirmationShown == false) return
    //if(e.target.id!="del"){

    // if clicked anywhere but on window, close window
    if(e.target.className!="deletionWindow"){
        document.getElementById("del").remove()
    }
    /*console.log(deleteWindows.pop().id)
    console.log(e.target.id)
    if(e.target!=deleteWindows.pop()){
        document.getElementById("del").remove()
    }*/
})


// open window that asks if you're sure yo want to delete video
function confirmDelete(vidId){
    //if(deletionWindowId!=false) return
    if(document.getElementById("del")!=null) return
    const confirmation = document.createElement("div")
    confirmation.setAttribute('id', 'del')
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
    confirmation.innerHTML = "Sure you want to delete this video?"
    confirmation.setAttribute('class', 'deletionWindow')

    const confirmationButtonsWrapper = document.createElement("div")
    confirmationButtonsWrapper.style.marginLeft = "auto"
    confirmationButtonsWrapper.style.marginRight = "auto"
    confirmationButtonsWrapper.setAttribute('class', 'deletionWindow')
    // create buttons for confirmation
    const yes = document.createElement("a")
    yes.innerHTML = "<button type='button'>Yes</button>" // make link look like a button    //yes.type = "submit"
    yes.href = baseurl+"/users/deleteVideo?vidId="+vidId
    yes.style.marginRight = ".2rem"
    yes.setAttribute('class', 'deletionWindow')
    const no = document.createElement("button")
    no.innerHTML = "No"
    no.setAttribute('class', 'deletionWindow')
    no.style.marginLeft = ".2rem"
    no.addEventListener("click", ()=>{
        confirmation.remove()
    })
    confirmationButtonsWrapper.appendChild(yes)
    confirmationButtonsWrapper.appendChild(no)
    confirmation.appendChild(confirmationButtonsWrapper)
    justCreated = true
    //confirmation.removeEventListener('click', ()=>{})
    document.getElementById("videoContainer"+vidId+"").appendChild(confirmation)

    //confirmationShown = true
}

// show dropdown menu elements on hover
document.getElementById('sign_in').addEventListener('mouseenter', showDropdownItems)

function showDropdownItems(){
    const dropdownItemsContainer = document.getElementById('dropdownItemsContainer')
    dropdownItemsContainer.style.opacity = "1"
    dropdownItemsContainer.style.transform = "translateY(0)"
    dropdownItemsContainer.style.pointerEvents = "auto"
}

// hide dropwodn menu elements

//document.getElementById('dropdownItemsContainer').addEventListener('mouseleave', hideDropdownItems)
addEventListener('click', hideDropdownItems)

function hideDropdownItems(){
    const dropdownItemsContainer = document.getElementById('dropdownItemsContainer')
    dropdownItemsContainer.style.opacity = "0"
    dropdownItemsContainer.style.transform = "translateY(-3rem)"
    dropdownItemsContainer.style.pointerEvents = "none"
}

//import './bootstrap';

const passwordElt = document.getElementById("password");

//document.getElementById("password").addEventListener("keyup", function(){
passwordElt.addEventListener("keyup", function(){
    const text = passwordElt.value
    if(text.length<7) passwordElt.style.backgroundColor = "rgb(255, 0, 0, .5)"
    else passwordElt.style.backgroundColor = "rgb(0, 255, 0, .4)"
})

const nicnkameElt = document.getElementById("name")
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

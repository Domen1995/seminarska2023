async function update_info_for_students(){
    const info_for_students = document.getElementById('info_for_students').value
    const token = document.getElementById('usersToken').content
    const response = await fetch("/teachers/updateStudentsInfo", {
        method: "post",
        body: JSON.stringify(info_for_students),
        headers: {
            'Content-Type' : 'Application/json',
            'X-CSRF-Token': token
        }
    })
    const responseStatus = response.status
    if(responseStatus==200) alert("Informations to students updated")
}

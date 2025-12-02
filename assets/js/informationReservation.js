fetch('http://172.16.194.254:5000/reservation', {
    credentials: 'include'
})
    .then(
        function (reponse) {
            if (reponse.status === 200) {
                reponse.json()
                    .then(
                        function (datas) {
                            let informationReservation = document.getElementById('informationReservation');
                            datas.forEach(
                                function (data) {
                                    informationReservation.appendChild(reservation(data));
                                }
                            )
                        }
                    )
            }
        }
    );

function reservation(dataUser) {
    let 
}
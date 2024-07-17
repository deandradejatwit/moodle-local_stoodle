export const init = (questionSet, answerSet) => {
    window.console.log(questionSet);
    window.console.log(answerSet);
    window.console.log("something");
};

export const other = () => {
    // fetch("quiz_activity.php").then(res => res.json()).then(data => {
    //     window.console.log(data);
    // }).catch(errorMsg => {
    //     window.console.log(errorMsg);
    // });
    var responseClone;
    fetch("quiz_activity.php")
        .then((response) => {
            if (!response.ok) { // Before parsing (i.e. decoding) the JSON data,
                                // check for any errors.
                // In case of an error, throw.
                throw new Error("Something went wrong!");
            }
            responseClone = response.clone();
            return response.json(); // Parse the JSON data.
        })
        .then((data) => {
            // This is where you handle what to do with the response.
            // alert(data); // Will alert: 42
            window.console.log(data);
        })
        .catch((rejectionReason) => {
            // This is where you handle errors.
            window.console.log('Error parsing JSON from response:', rejectionReason, responseClone); // 4
            responseClone.text() // 5
            .then(function (bodyText) {
                window.console.log('Received the following instead of valid JSON:', bodyText); // 6
            });
        });
};

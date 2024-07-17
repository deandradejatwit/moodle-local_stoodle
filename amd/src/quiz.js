export const init = () => {
    const questionDiv = document.querySelector(".js-answer-area");
    const questionSet = JSON.parse(document.getElementById("question-set").innerHTML);
    const answerSet = JSON.parse(document.getElementById("answer-set").innerHTML);
    // window.console.log(questionSet);
    // window.console.log(answerSet);

    for (const key in Object.values(questionSet)) {
        // Check if it's a multiple choice question
        if (Object.values(questionSet)[key].is_multiple_choice === 0) {
            window.console.log("Not multiple choice");
            continue;
        }

        // Create a new div to house the question-answer pair
        const newDiv = document.createElement("div");
        newDiv.appendChild(document.createElement("p"))
            .textContent = "Question " + (parseInt(key) + 1) + ": " + Object.values(questionSet)[key].question_text;
        createMultipleChoiceQuestion(newDiv, Object.values(questionSet)[key].id, answerSet);
        questionDiv.appendChild(newDiv);
    }

    /**
     * Creates a multiple choice question.
     *
     * @param {object} parent
     * @param {int} questionId
     * @param {object} answerSet
     */
    function createMultipleChoiceQuestion(parent, questionId, answerSet) {
        for (const key in Object.values(answerSet)) {
            // Match answer id to question id
            if (Object.values(answerSet)[key].question_id === questionId) {
                createInputNode(parent, "someid", ("someName" + questionId), Object.values(answerSet)[key].option_text);
            }
        }
    }

    /**
     * Creates a multiple choice question.
     *
     * @param {object} parent
     * @param {string} id
     * @param {string} name
     * @param {string} value
     */
    function createInputNode(parent, id, name, value) {
        // Create a radio input
        const radio = document.createElement("input");
        radio.type = "radio";
        radio.id = id;
        radio.name = name;
        radio.value = value;

        // Create the label to go with it
        const label = document.createElement("label");
        label.appendChild(document.createTextNode(value));

        // Append them to the parent
        parent.appendChild(radio);
        parent.appendChild(label);
        parent.appendChild(document.createElement("br"));
    }
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

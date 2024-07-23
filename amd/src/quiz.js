export const init = () => {
    const questionSet = JSON.parse(document.getElementById("question-set").innerHTML);
    const answerSet = JSON.parse(document.getElementById("answer-set").innerHTML);
    const questionDiv = document.querySelector(".js-answer-area");
    const scoreArea = document.querySelector(".score");
    const totalQuestions = Object.values(questionSet).length;
    // window.console.log(questionSet);
    // window.console.log(answerSet);

    document.querySelector(".submit-button").addEventListener('click', () => {
        questionValidation();
    });

    scoreArea.innerHTML = "Score: 0 / " + totalQuestions;
    for (const key in Object.values(questionSet)) {
        // Create a new div to house the question-answer pair
        const newDiv = document.createElement("div");
        newDiv.appendChild(document.createElement("p"))
            .textContent = "Question " + (parseInt(key) + 1) + ": " + Object.values(questionSet)[key].question_text;

        // Check if it's a multiple choice or open-response question
        if (Object.values(questionSet)[key].is_multiple_choice === 0) {
            newDiv.id = "open-response";
            createOpenResponseQuestion(newDiv, Object.values(questionSet)[key].id);
        } else {
            newDiv.id = "multiple-choice";
            createMultipleChoiceQuestion(newDiv, Object.values(questionSet)[key].id, answerSet);
        }
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
            const dbId = Object.values(answerSet)[key].stoodle_quiz_questionsid;
            if (dbId === questionId) {
                createInputNode(parent, "someid", (questionId), Object.values(answerSet)[key].option_text);
            } else {
                window.console.log("Something wrong: " + dbId + " does not equal " + questionId);
            }
        }
    }

    /**
     * Creates a open response question.
     *
     * @param {object} parent
     * @param {string} name
     */
    function createOpenResponseQuestion(parent, name) {
        const textInput = document.createElement("input");
        textInput.type = "text";
        textInput.name = name;
        parent.appendChild(textInput);
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

    /**
     * Validates the questions on the quiz. Checks if right or wrong.
     *
     */
    function questionValidation() {
        let numCorrect = 0;
        for (const key in Object.values(questionSet)) {
            // Getting the selected radio button value
            const question = Object.values(questionSet)[key].id;
            let option = null;

            if (document.querySelector('input[name = "' + question + '"]').parentElement.id === "multiple-choice") {
                option = document.querySelector('input[name = "' + question + '"]:checked');
            } else {
                option = document.querySelector('input[name = "' + question + '"]');
            }

            if (option === null) {
                alert("Question " + (parseInt(key) + 1) + " is unanswered");
                return;
            }

            // Comparing them to the answers
            for (const answerKey in Object.values(answerSet)) {
                const answerText = Object.values(answerSet)[answerKey].option_text;
                const answerIsCorrect = parseInt(Object.values(answerSet)[answerKey].is_correct);
                if (answerText === option.value && answerIsCorrect === 1) {
                    window.console.log("Question " + (parseInt(key) + 1) + " is correct");
                    numCorrect++;
                    break;
                } else if (answerText === option.value && answerIsCorrect === 0) {
                    window.console.log("Question " + (parseInt(key) + 1) + " is wrong");
                    break;
                }
            }
        }

        scoreArea.innerText = "Score: " + numCorrect + " / " + totalQuestions;
    }
};

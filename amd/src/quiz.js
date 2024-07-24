export const init = () => {
    const questionSet = JSON.parse(document.getElementById("question-set").innerHTML);
    const answerSet = JSON.parse(document.getElementById("answer-set").innerHTML);
    const questionDiv = document.querySelector(".js-answer-area");
    const scoreArea = document.querySelector(".score");
    const totalQuestions = Object.values(questionSet).length;

    scoreArea.innerHTML = "Score: 0 / " + totalQuestions;
    document.querySelector(".submit-button").addEventListener('click', () => {
        questionValidation();
    });

    // Create questions and options
    for (const key in Object.values(questionSet)) {
        const newDiv = document.createElement("div");
        newDiv.id = "stoodle-div";
        const questionText = document.createElement("p");
        questionText.id = "stoodle-question-text";
        questionText.textContent = "Question " + (parseInt(key) + 1) + ": " + Object.values(questionSet)[key].question_text;
        newDiv.appendChild(questionText);

        // Check if it's a multiple choice or open-response question
        if (parseInt(Object.values(questionSet)[key].is_multiple_choice) === 0) {
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
     * @param {integer} questionId
     * @param {object} answerSet
     */
    function createMultipleChoiceQuestion(parent, questionId, answerSet) {
        for (const key in Object.values(answerSet)) {
            // Match answer id to question id
            const dbId = Object.values(answerSet)[key].stoodle_quiz_questionsid;
            if (dbId === questionId) {
                createInputNode(parent, "someid", (questionId), Object.values(answerSet)[key].option_text);
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
            const question = Object.values(questionSet)[key].id;
            const parent = document.querySelector('input[name = "' + question + '"]').parentElement;
            let option = null;

            if (parent.id === "multiple-choice") {
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
                const questionText = "Question " + (parseInt(key) + 1) + ": " + Object.values(questionSet)[key].question_text;
                const answerText = Object.values(answerSet)[answerKey].option_text;
                const answerIsCorrect = parseInt(Object.values(answerSet)[answerKey].is_correct);
                if (answerText === option.value && answerIsCorrect === 1) {
                    window.console.log("Question " + (parseInt(key) + 1) + " is correct");
                    numCorrect++;
                    parent.children[0].innerText = questionText + " \u{2705}";
                    break;
                } else if (answerText === option.value && answerIsCorrect === 0) {
                    window.console.log("Question " + (parseInt(key) + 1) + " is wrong");
                    parent.children[0].innerText = questionText + " \u{274C}";
                    break;
                } else if (parent.id === "open-response") {
                    parent.children[0].innerText = questionText + " \u{274C} (manual review required)";
                }
            }
        }

        scoreArea.innerText = "Score: " + numCorrect + " / " + totalQuestions;
    }
};

export const init = () => {
    const questionSet = JSON.parse(document.getElementById("question-set").innerHTML);
    const answerSet = JSON.parse(document.getElementById("answer-set").innerHTML);
    const questionDiv = document.querySelector(".js-answer-area");
    const scoreArea = document.querySelector(".score");
    const totalQuestions = Object.values(questionSet).length;

    const newSet = createFullSet(questionSet, answerSet);
    const typeSet = declareQuestionTypes(questionSet, answerSet);
    window.console.log(newSet);
    window.console.log(typeSet);

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

        switch (typeSet[key]) {
            default:
            case 0:
                newDiv.id = "open-response";
                createOpenResponseQuestion(newDiv, ("option_" + key));
                break;
            case 1:
                newDiv.id = "multiple-choice";
                for (const element in newSet.get(Object.values(questionSet)[key].question_text)[0]) {
                    const optionText = newSet.get(Object.values(questionSet)[key].question_text)[0][element];
                    createInputNodeRadio(newDiv, ("option_" + key), key, optionText);
                }
                break;
            case 2:
                newDiv.id = "select-all";
                for (const element in newSet.get(Object.values(questionSet)[key].question_text)[0]) {
                    const optionText = newSet.get(Object.values(questionSet)[key].question_text)[0][element];
                    createInputNodeCheckBox(newDiv, ("option_" + key), key, optionText);
                }
                break;
        }
        questionDiv.appendChild(newDiv);
    }

    /**
     * Creates a open response question.
     *
     * @param {object} parent
     * @param {string} name
     */
    function createOpenResponseQuestion(parent, name) {
        const label = document.createElement("label");
        const textInput = document.createElement("textarea");
        textInput.id = name;
        textInput.cols = 100;
        label.appendChild(textInput);
        parent.appendChild(label);
    }

    /**
     * Creates a radio button.
     *
     * @param {object} parent
     * @param {string} id
     * @param {string} name
     * @param {string} value
     */
    function createInputNodeRadio(parent, id, name, value) {
        // Create a radio input
        const radio = document.createElement("input");
        radio.type = "radio";
        radio.id = id;
        radio.name = name;
        radio.value = value;

        // Create the label to go with it
        const label = document.createElement("label");
        label.appendChild(radio);
        label.appendChild(document.createTextNode(value));

        // Append them to the parent
        parent.appendChild(label);
        parent.appendChild(document.createElement("br"));
    }

    /**
     * Creates a checkbox button.
     *
     * @param {object} parent
     * @param {string} id
     * @param {string} name
     * @param {string} value
     */
    function createInputNodeCheckBox(parent, id, name, value) {
        // Create a radio input
        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.id = id;
        checkbox.name = name;
        checkbox.value = value;

        // Create the label to go with it
        const label = document.createElement("label");
        label.appendChild(checkbox);
        label.appendChild(document.createTextNode(value));

        // Append them to the parent
        parent.appendChild(label);
        parent.appendChild(document.createElement("br"));
    }

    /**
     * Validates the questions on the quiz. Checks if right or wrong.
     *
     */
    function questionValidation() {
        let numCorrect = 0;
        const correctHex = "\u{2705}";
        const incorrectHex = "\u{274C}";

        for (const key in Object.values(questionSet)) {
            const dbQuestionText = Object.values(questionSet)[key].question_text;
            const htmlQuestionText = "Question " + (parseInt(key) + 1) + ": " + dbQuestionText;

            if (typeSet[key] === 0) {
                // Check Open Response
                const option = document.getElementById("option_" + key);
                if (option === null) {
                    alert("Question " + (parseInt(key) + 1) + " is unanswered");
                    return;
                }
                const parent = option.parentElement.parentElement.children[0];
                if (option.value === newSet.get(dbQuestionText)[1][0]) {
                    window.console.log("Question " + (parseInt(key) + 1) + " is correct");
                    numCorrect++;
                    parent.innerText = htmlQuestionText + " " + correctHex;
                } else {
                    parent.innerText = htmlQuestionText + " " + incorrectHex + " (manual review required)";
                }
            } else if (typeSet[key] === 1) {
                // Check Multiple Choice
                const option = document.querySelector('input[name = "' + key + '"]:checked');
                if (option === null) {
                    alert("Question " + (parseInt(key) + 1) + " is unanswered");
                    return;
                }
                const parent = option.parentElement.parentElement.children[0];
                if (newSet.get(dbQuestionText)[1][0] === option.value) {
                    window.console.log("Question " + (parseInt(key) + 1) + " is correct");
                    numCorrect++;
                    parent.innerText = htmlQuestionText + " " + correctHex;
                } else {
                    parent.innerText = htmlQuestionText + " " + incorrectHex;
                }
            } else if (typeSet[key] === 2) {
                // Check Select All
                const option = document.querySelectorAll('input[name="' + key + '"]:checked');
                if (option.length < 1) {
                    alert("Question " + (parseInt(key) + 1) + " is unanswered");
                    return;
                }
                const parent = document.querySelector('input[name="' + key + '"]:checked').parentElement.parentElement.children[0];
                let selectAllCorrectCounter = 0;
                if (newSet.get(dbQuestionText)[1].length !== option.length) {
                    parent.innerText = htmlQuestionText + " " + incorrectHex;
                    continue;
                }
                for (let i = 0; i < newSet.get(dbQuestionText)[1].length; i++) {
                    if (newSet.get(dbQuestionText)[0][i] === option[i].value) {
                        selectAllCorrectCounter++;
                    }
                }
                if (selectAllCorrectCounter === newSet.get(dbQuestionText)[1].length) {
                    numCorrect++;
                    window.console.log("Question " + (parseInt(key) + 1) + " is correct");
                    parent.innerText = htmlQuestionText + " " + correctHex;
                } else {
                    parent.innerText = htmlQuestionText + " " + incorrectHex;
                }
            }
        }

        scoreArea.innerText = "Score: " + numCorrect + " / " + totalQuestions;
    }

    /**
     * Returns an array with question types.
     * 0 - Open Response,
     * 1 - Multiple Choice,
     * 2 - Select All
     *
     * @param {Object} questions
     * @param {Object} options
     * @return {Array} Resulting array with question types.
     */
    function declareQuestionTypes(questions, options) {
        const questionTypesArray = new Array(Object.values(questions).length);
        for (const questionKey in Object.values(questions)) {
            const questionId = parseInt(Object.values(questions)[questionKey].id);
            let count = 0;
            for (const answerKey in Object.values(options)) {
                const answerObject = Object.values(options)[answerKey];
                if (parseInt(answerObject.stoodle_quiz_questionsid) === questionId && parseInt(answerObject.is_correct) === 1) {
                    count++;
                }
            }
            if (count > 1) {
                questionTypesArray[questionKey] = 2;
            } else if (count === 1 && parseInt(Object.values(questions)[questionKey].is_multiple_choice) === 1) {
                questionTypesArray[questionKey] = 1;
            } else {
                questionTypesArray[questionKey] = 0;
            }
        }
        return questionTypesArray;
    }


    /**
     * Return an array of questions and answers.
     *
     * @param {Object} qSet
     * @param {Object} oSet
     * @return {Map} Resulting array
     */
    function createFullSet(qSet, oSet) {
        let resultSet = new Map();
        for (const qKey in Object.values(qSet)) {
            let optionArray = [];
            let answerArray = [];
            for (const oKey in Object.values(oSet)) {
                if (Object.values(qSet)[qKey].question_number === Object.values(oSet)[oKey].stoodle_quiz_questionsid) {
                    optionArray.push(Object.values(oSet)[oKey].option_text);
                    if (parseInt(Object.values(oSet)[oKey].is_correct) === 1) {
                        answerArray.push(Object.values(oSet)[oKey].option_text);
                    }
                }
            }
            resultSet.set(Object.values(qSet)[qKey].question_text, [optionArray, answerArray]);
        }
        return resultSet;
    }
};

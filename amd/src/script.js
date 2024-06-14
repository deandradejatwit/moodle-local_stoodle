export const init = () => {
    const trueOrFalse = false;
    window.console.log("Hello World, welcome to Moodle!");
    const dog = (coolParam) => {
        if (coolParam) {
            window.console.log('Woof or something');
        }
    };
    dog(trueOrFalse);
};
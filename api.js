document.getElementById('sendButton').addEventListener('click', function() {
    var inputText = document.getElementById('inputText').value;

    addMessage(inputText, "input");

    var data = {
        text: inputText
    };

    fetch('https://trial.jonathanwebworks.com/CapyBara/pleasegod.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)  
    })
    .then(response => response.text())  
    .then(rawResponse => {
        let cleanedResponse = rawResponse.replace(/^'''|'''$/g, '');

        let data = JSON.parse(cleanedResponse);

        console.log('Cleaned Response:', data);  
        if (data.generated_text) {
         
            let cleanedGeneratedText = data.generated_text.replace(/```json|```/g, '').trim();

         
            let innerData = JSON.parse(cleanedGeneratedText);

            console.log('Inner Response Text:', innerData.responseText);
            console.log('Inner Emotion Felt:', innerData.emotionFelt);

     
            addMessage(innerData.responseText, "response");
            changeIMG(innerData.emotionFelt);
        } else {
            console.error("Unexpected response structure.");
        }
        
        document.getElementById('inputText').value = '';
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

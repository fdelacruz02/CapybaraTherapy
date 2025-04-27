function addMessage(text, sender) {
   
    const chatDisplay = document.getElementById('ChatDisplay');


    const messageElement = document.createElement('section');


    messageElement.textContent = text;

  
    if (sender === 'input') {
        messageElement.classList.add('input');
    } else if (sender === 'response') {
        messageElement.classList.add('response');
    }


    chatDisplay.appendChild(messageElement);
}

function changeIMG(input) {
    let happy = "happy.gif";
    let sad = "sad.gif";
    let mad = "mad.gif";
    
   image = document.getElementById('emotion_img')

   
     if (input === "anxious") {
        image.src = sad;
     } else if (input === "overwhelmed") {
        image.src = mad;
     } else if (input === "lonely") {
        image.src = sad;
     } else if (input === "numb") {
        image.src = sad;   
     } else if (input === "excited") {
        image.src = happy;
    } else {

  }
}
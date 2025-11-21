class MessageBox {
    constructor(id, option) {
        this.id = id;
        this.option = option;
    }
    show(msg,color, label="x", callback=null) {
        if (this.id === null || typeof this.id === "undefined") {
            // if the ID is not set or if the ID is undefined

            throw "Please set the 'ID' of the message box container.";
        }
        if (msg === "" || typeof msg === "undefined" || msg === null) {
            // If the 'msg' parameter is not set, throw an error

            throw "The 'msg' parameter is empty.";
        }
        if (typeof label === "undefined" || label === null) {
            // Of the label is undefined, or if it is null

            label = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle mb-2 text-light"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>';
        }
        let option = this.option;
        let msgboxArea = document.querySelector(this.id);
        let msgboxBox = document.createElement("DIV");
        let msgboxContent = document.createElement("DIV");
        let msgboxClose = document.createElement("A");
        if (msgboxArea === null) {
            // If there is no Message Box container found.

            throw "The Message Box container is not found.";
        }
        // Content area of the message box
        msgboxContent.classList.add("msgbox-content");
        msgboxContent.innerHTML = "<div style='display:flex;'><svg width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-bell mr-2'><path d='M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9'></path><path d='M13.73 21a2 2 0 0 1-3.46 0'></path></svg><span class='mt-0 ' style='font-size:16px;'>"+msg+"</span></div>";
        // msgboxContent.innerText = msg;
        // Close burtton of the message box
        msgboxClose.classList.add("msgbox-close");
        msgboxClose.classList.add("text-end");
        msgboxClose.setAttribute("href", "#");
        msgboxClose.innerHTML = label;
        // Container of the Message Box element
        msgboxBox.classList.add("msgbox-box");
        if(!color){
            color = 'error';
        }
        msgboxBox.classList.add(color);
        msgboxBox.appendChild(msgboxContent);
        if (option.hideCloseButton === false || typeof option.hideCloseButton === "undefined") {
            // If the hideCloseButton flag is false, or if it is undefined

            // Append the close button to the container
            msgboxBox.appendChild(msgboxClose);
        }
        msgboxArea.appendChild(msgboxBox);
        msgboxClose.addEventListener("click", (evt)=>{
            evt.preventDefault();

            if (msgboxBox.classList.contains("msgbox-box-hide")) {
                // If the message box already have 'msgbox-box-hide' class
                // This is to avoid the appearance of exception if the close
                // button is clicked multiple times or clicked while hiding.

                return;
            }
            this.hide(msgboxBox, callback);
        }
        );
        if (option.closeTime > 0) {
            this.msgboxTimeout = setTimeout(()=>{
                this.hide(msgboxBox, callback);
            }
            , option.closeTime);
        }
    }
    hide(msgboxBox, callback) {
        if (msgboxBox !== null) {
            // If the Message Box is not yet closed

            msgboxBox.classList.add("msgbox-box-hide");
        }
        msgboxBox.addEventListener("transitionend", ()=>{
            if (msgboxBox !== null) {
                // If the Message Box is not yet closed
                msgboxBox.parentNode.removeChild(msgboxBox);
                clearTimeout(this.msgboxTimeout);
                if (callback !== null) {
                    // If the callback parameter is not null
                    callback();
                }
            }
        }
        );
    }
}
let msgboxbox = new MessageBox("#msgbox-area",{
    closeTime: 7000,
    hideCloseButton: false
});


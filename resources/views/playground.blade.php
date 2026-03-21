<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bad UI</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Lucida Console', 'Courier New', monospace;
            margin: 0;
            padding: 40px 20px;
            background-color: #0000AA;
            color: #FFFFFF;
            min-height: 100vh;
        }

        h1 {
            color: #FFFFFF;
            font-size: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            background-color: #AAAAAA;
            color: #0000AA;
            padding: 12px;
            font-weight: bold;
            letter-spacing: 4px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
            background-color: #0000AA;
        }

        .bsod-header {
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: 2px;
            padding: 20px;
            border: 3px solid #FFFFFF;
            background-color: #0000AA;
        }

        .error-text {
            margin-bottom: 30px;
            line-height: 2;
            font-size: 1.1rem;
            padding: 20px;
            border-left: 4px solid #FFFFFF;
            background-color: #000088;
        }

        textarea {
            width: 100%;
            min-height: 150px;
            padding: 16px;
            background: #0000AA;
            border: 4px solid #FFFFFF;
            border-radius: 0;
            color: #FFFFFF;
            font-family: 'Lucida Console', 'Courier New', monospace;
            font-size: 1rem;
            resize: vertical;
            box-sizing: border-box;
        }

        textarea:focus {
            outline: none;
            border-color: #AAAAAA;
            background: #0000CC;
        }

        textarea::placeholder {
            color: #8888FF;
        }

        button {
            margin-top: 20px;
            padding: 28px 60px;
            background: #0000AA;
            color: #FFFFFF;
            border: 4px solid #FFFFFF;
            border-radius: 0;
            font-family: 'Lucida Console', 'Courier New', monospace;
            font-size: 2rem;
            font-weight: 900;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 6px;
            box-shadow: 6px 6px 0 #AAAAAA;
            transition: all 0.1s ease;
            width: 100%;
        }

        button:hover {
            background: #0000DD;
            box-shadow: 4px 4px 0 #AAAAAA;
        }

        button:active {
            transform: translate(3px, 3px);
            box-shadow: none;
        }

        button:disabled {
            background: #000055;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
            border-color: #666666;
            color: #AAAAAA;
        }

        .output {
            margin-top: 30px;
            padding: 20px;
            background: #0000AA;
            border: 4px solid #FFFFFF;
            border-radius: 0;
            min-height: 120px;
            white-space: pre-wrap;
            font-size: 1rem;
            line-height: 1.8;
            font-family: 'Lucida Console', 'Courier New', monospace;
        }

        .output.error {
            border-color: #FFFF00;
            color: #FFFF00;
            background: #AA0000;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .footer-text {
            margin-top: 30px;
            text-align: center;
            font-size: 1rem;
        }

        .press-key {
            background-color: #AAAAAA;
            color: #0000AA;
            padding: 16px;
            margin-top: 30px;
            text-align: center;
            font-weight: bold;
            font-size: 1.1rem;
            border: 2px solid #FFFFFF;
            letter-spacing: 2px;
        }

        .error-code {
            text-align: center;
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #FFFFFF;
            text-shadow: 2px 2px 0 #000088;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">*** STOP: 0x0000007B</div>
        
        <h1>FATAL EXCEPTION</h1>

        <div class="bsod-header">
            A fatal exception 0E has occurred at 0028:C0011E36 in VXD VMM(01) +<br>
            00010E36. The current application will be terminated.
        </div>

        <div class="error-text">
            * Press ENTER to submit your UI modification request<br>
            * Press ESC to ignore and continue<br>
            * Press CTRL+ALT+DEL to restart your computer<br>
            * Press any key to continue _
        </div>

        <form id="prompt-form">
            <textarea id="prompt" placeholder="Describe a UI change... e.g. 'add a blue banner at the top'" autofocus></textarea>
            <button type="submit" id="submit-btn">Send</button>
        </form>

        <div class="output" id="output">Output will appear here...</div>

        <div class="press-key">
            Press CTRL+ALT+DEL to restart your computer. You will lose any unsaved information in all applications.
        </div>
    </div>

    <script>
        function rebindForm() {
            const form = document.getElementById('prompt-form');
            const promptTextarea = document.getElementById('prompt');
            if (!form) return;
            
            form.addEventListener('submit', handleSubmit);
            
            if (promptTextarea) {
                promptTextarea.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        form.dispatchEvent(new Event('submit'));
                    }
                });
            }
        }

        let promptInput, submitBtn, output;

        async function handleSubmit(e) {
            e.preventDefault();

            promptInput = document.getElementById('prompt');
            submitBtn = document.getElementById('submit-btn');
            output = document.getElementById('output');

            const prompt = promptInput.value.trim();
            if (!prompt) return;

            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';
            output.textContent = 'Waiting for Claude...';
            output.classList.remove('error');

            try {
                const response = await fetch('/playground', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ prompt }),
                });

                const data = await response.json();

                if (response.ok) {
                    output.textContent = 'Applying changes...';
                    // Fetch the updated page and swap in new HTML/CSS
                    const pageRes = await fetch('/playground');
                    const pageHtml = await pageRes.text();
                    const parser = new DOMParser();
                    const newDoc = parser.parseFromString(pageHtml, 'text/html');

                    // Preserve form state
                    const savedPrompt = promptInput.value;

                    // Swap styles
                    const newStyle = newDoc.querySelector('style');
                    const oldStyle = document.querySelector('style');
                    if (newStyle && oldStyle) {
                        oldStyle.textContent = newStyle.textContent;
                    }

                    // Swap body content but keep the script
                    const newBody = newDoc.querySelector('body');
                    const oldScript = document.querySelector('script');
                    if (newBody) {
                        // Remove script from new body before swapping
                        const newScript = newBody.querySelector('script');
                        if (newScript) newScript.remove();
                        // Replace everything except the script
                        const scriptClone = oldScript;
                        document.body.innerHTML = newBody.innerHTML;
                        document.body.appendChild(scriptClone);
                    }

                    // Re-bind elements and restore state
                    const newPromptInput = document.getElementById('prompt');
                    const newOutput = document.getElementById('output');
                    if (newPromptInput) newPromptInput.value = savedPrompt;
                    if (newOutput) newOutput.textContent = 'Changes applied!';

                    // Re-bind form and keyboard shortcut
                    rebindForm();
                    rebindTextarea();
                } else {
                    output.textContent = data.error || 'Something went wrong.';
                    output.classList.add('error');
                }
            } catch (err) {
                output.textContent = 'Network error: ' + err.message;
                output.classList.add('error');
            } finally {
                const btn = document.getElementById('submit-btn');
                if (btn) { btn.disabled = false; btn.textContent = 'Send'; }
            }
        }

        function rebindTextarea() {
            const ta = document.getElementById('prompt');
            if (!ta) return;
            ta.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    document.getElementById('prompt-form').dispatchEvent(new Event('submit'));
                }
            });
        }

        rebindForm();
        rebindTextarea();
    </script>
</body>
</html>
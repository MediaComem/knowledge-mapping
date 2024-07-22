import subprocess
import threading
from flask import Flask, jsonify
import os
from os.path import abspath, dirname, join
from enum import Enum
from dotenv import load_dotenv

load_dotenv()

class ScriptStatus(Enum):
    RUNNING = "Running"
    COMPLETED = "Completed"
    ERROR = "Error"
    NEVER_RAN = "Never Ran"

# Dictionary to store the status of scripts
status = ScriptStatus.NEVER_RAN 

def run_external_script(script_path):
    global status
    try:
        status = ScriptStatus.RUNNING

        # Run ML script on Zotero library
        process = subprocess.Popen(['python3', script_path, "--id", os.environ.get("ZOTERO_GROUP_ID"), "--key", os.environ.get("ZOTERO_API_KEY")], stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True)

        # Capture output and errors
        stdout, stderr = process.communicate()

        if process.returncode == 0:
            status = ScriptStatus.COMPLETED
        else:
            status = ScriptStatus.ERROR
    except Exception as e:
        status = ScriptStatus.ERROR

app = Flask(__name__)

@app.route('/run', methods=['GET'])
def start_script():
    global status
    if status == ScriptStatus.RUNNING:
        return jsonify({
            'status': status.value, 
            'message': 'Script already running'
            }), 200
    else:
        script_path = abspath(join(dirname(__file__), 'process.py'))
        threading.Thread(target=run_external_script, args=(script_path,)).start()
        return jsonify({
            'status': status.value,
            'message': 'Script started successfully'
            }), 200

@app.route('/status', methods=['GET'])
def get_script_status():
    global status
    return jsonify({'status': status.value}), 200

if __name__ == '__main__':
    app.run(debug=True)
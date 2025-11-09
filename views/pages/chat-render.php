<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            padding: 10px;
            background: #f5f5f5;
        }
        .chat-container {
            max-width: 100%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="chat-container" id="chat-container">
        <iframe id="n8n-iframe" src="https://agqiom.ezn8n.com/webhook/e8edc63d-0051-4d71-8cdf-d57ef05602d6/chat" frameborder="0" width="100%" height="600px" style="border: none;"></iframe>
    </div>
    
    <script>
        (function() {
            const container = document.getElementById('chat-container');
            const iframe = document.getElementById('n8n-iframe');
            let jsonFound = false;
            let checkCount = 0;
            const maxChecks = 100;
            
            function parseJSON(text) {
                if (!text || !text.includes('"p"') || !text.includes('"items"')) {
                    return null;
                }
                
                const patterns = [
                    /\{[\s\S]*?"p"[\s\S]*?"items"[\s\S]*?\}/,
                    /\{[\s\S]*?"p":\s*"[^"]*"[\s\S]*?"items":\s*\[[\s\S]*?\][\s\S]*?\}/
                ];
                
                for (let pattern of patterns) {
                    const matches = text.match(pattern);
                    if (matches) {
                        for (let match of matches) {
                            try {
                                const data = JSON.parse(match.trim());
                                if (data && data.p && Array.isArray(data.items)) {
                                    return data;
                                }
                            } catch (e) {
                                try {
                                    const cleaned = match.replace(/[\r\n\t]/g, ' ').replace(/\s+/g, ' ').trim();
                                    const data = JSON.parse(cleaned);
                                    if (data && data.p && Array.isArray(data.items)) {
                                        return data;
                                    }
                                } catch (e2) {
                                }
                            }
                        }
                    }
                }
                return null;
            }
            
            function renderHTML(data) {
                if (!data || jsonFound) return;
                jsonFound = true;
                
                let html = '<div style="padding: 8px; font-size: 13px; line-height: 1.4; color: #333;">';
                
                if (data.p) {
                    html += '<div style="margin-bottom: 8px; color: #555;">' + escapeHtml(data.p) + '</div>';
                }
                
                if (data.h2) {
                    html += '<h2 style="margin: 8px 0; font-size: 16px; font-weight: 600; color: #ff6b6b;">' + escapeHtml(data.h2) + '</h2>';
                }
                
                if (data.items && Array.isArray(data.items)) {
                    html += '<div style="display: flex; flex-direction: column; gap: 8px; margin-top: 10px;">';
                    
                    data.items.forEach(function(item) {
                        html += '<div style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 8px; background: #f9f9f9; display: flex; gap: 8px;">';
                        
                        if (item.img) {
                            html += '<div style="flex-shrink: 0; width: 60px; height: 60px; overflow: hidden; border-radius: 4px;">';
                            html += '<img src="' + escapeHtml(item.img) + '" alt="' + escapeHtml(item.h3 || '') + '" style="width: 100%; height: 100%; object-fit: cover;">';
                            html += '</div>';
                        }
                        
                        html += '<div style="flex: 1; min-width: 0;">';
                        
                        if (item.h3) {
                            html += '<div style="font-weight: 600; font-size: 14px; margin-bottom: 4px; color: #333;">' + escapeHtml(item.h3) + '</div>';
                        }
                        
                        if (item.desc) {
                            html += '<div style="font-size: 12px; color: #666; margin-bottom: 4px;">' + escapeHtml(item.desc) + '</div>';
                        }
                        
                        html += '<div style="display: flex; gap: 8px; flex-wrap: wrap; font-size: 11px; color: #888; margin-bottom: 4px;">';
                        if (item.price) {
                            html += '<span><strong style="color: #ff6b6b;">' + escapeHtml(item.price) + '</strong></span>';
                        }
                        if (item.size) {
                            html += '<span>Size: ' + escapeHtml(item.size) + '</span>';
                        }
                        if (item.stock) {
                            html += '<span>Còn: ' + escapeHtml(item.stock) + '</span>';
                        }
                        html += '</div>';
                        
                        if (item.link) {
                            html += '<a href="' + escapeHtml(item.link) + '" target="_blank" style="display: inline-block; padding: 4px 8px; background: #ff6b6b; color: white; text-decoration: none; border-radius: 4px; font-size: 11px; margin-top: 4px;">Xem chi tiết</a>';
                        }
                        
                        html += '</div></div>';
                    });
                    
                    html += '</div>';
                }
                
                if (data.note) {
                    html += '<div style="margin-top: 10px; padding: 8px; background: #e8f4f8; border-radius: 4px; font-size: 12px; color: #555;">' + escapeHtml(data.note) + '</div>';
                }
                
                html += '</div>';
                
                iframe.style.display = 'none';
                container.innerHTML = html;
                
                if (window.parent && window.parent !== window) {
                    window.parent.postMessage({ type: 'chat-json-rendered', data: data }, '*');
                }
            }
            
            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }
            
            function checkIframe() {
                if (jsonFound || checkCount >= maxChecks) {
                    return;
                }
                checkCount++;
                
                try {
                    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                    if (iframeDoc && iframeDoc.body) {
                        const text = iframeDoc.body.innerText || iframeDoc.body.textContent || '';
                        if (text) {
                            const data = parseJSON(text);
                            if (data) {
                                renderHTML(data);
                                return;
                            }
                        }
                        
                        const elements = iframeDoc.querySelectorAll('*');
                        for (let elem of elements) {
                            const elemText = elem.innerText || elem.textContent || '';
                            if (elemText && elemText.includes('"p"') && elemText.includes('"items"')) {
                                const data = parseJSON(elemText);
                                if (data) {
                                    renderHTML(data);
                                    return;
                                }
                            }
                        }
                    }
                } catch (e) {
                }
            }
            
            iframe.addEventListener('load', function() {
                setTimeout(function() {
                    const interval = setInterval(function() {
                        checkIframe();
                        if (jsonFound || checkCount >= maxChecks) {
                            clearInterval(interval);
                        }
                    }, 500);
                    
                    setTimeout(function() {
                        clearInterval(interval);
                    }, 30000);
                }, 1000);
            });
            
            window.addEventListener('message', function(event) {
                if (jsonFound) return;
                
                if (event.data && typeof event.data === 'object' && event.data.p && Array.isArray(event.data.items)) {
                    renderHTML(event.data);
                } else if (typeof event.data === 'string') {
                    const data = parseJSON(event.data);
                    if (data) {
                        renderHTML(data);
                    }
                }
            });
        })();
    </script>
</body>
</html>


<div class="hero" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/assets/images/shoes_store.jpg'); background-size: cover; background-position: center; background-color: transparent; height: 500px; display: flex; align-items: center; justify-content: center; text-align: center; color: white; margin-bottom: 40px; position: relative;">
    <div class="hero-content">
        <h1>Step into Style</h1>
        <p>Discover the perfect pair of shoes for every occasion. From casual to formal, we've got you covered.</p>
        <a href="/index.php?controller=products&action=index" class="btn">Shop Now</a>
    </div>
</div>

<section class="featured-products">
    <div class="section-title">
        <h2>Featured Products</h2>
    </div>
    <div class="products">
        <?php if (empty($featuredProducts)): ?>
            <p style="text-align: center; color: #777;">No featured products available.</p>
        <?php else: ?>
            <?php foreach ($featuredProducts as $product): ?>
                <div class="product-card">
                    <div class="product-img">
                        <img src="<?php echo htmlspecialchars($product['Image'] ?: '/placeholder.svg?height=200&width=300'); ?>" alt="<?php echo htmlspecialchars($product['ProductName']); ?>">
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($product['ProductName']); ?></h3>
                        <div class="price">$<?php echo number_format($product['Price'], 2); ?></div>
                        <a href="/index.php?controller=products&action=detail&id=<?php echo $product['ProductID']; ?>" class="btn">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- <section class="categories">
    <div class="section-title">
        <h2>Shop by Category</h2>
    </div>
    <div class="products">
        <//?php if (empty($categories)): ?>
            <p style="text-align: center; color: #777;">No categories available.</p>
        <//?php else: ?>
            <//?php foreach ($categories as $category): ?>
                <div class="product-card">
                    <div class="product-img">
                        <img src="/placeholder.svg?height=200&width=300" alt="<//?php echo htmlspecialchars($category['CategoryName']); ?>">
                    </div>
                    <div class="product-info">
                        <h3><//?php echo htmlspecialchars($category['CategoryName']); ?></h3>
                        <a href="/index.php?controller=products&action=index&category=<//?php echo urlencode($category['CategoryID']); ?>" class="btn">Shop Now</a>
                    </div>
                </div>
            <//?php endforeach; ?>
        <//?php endif; ?>
    </div>
</section> -->

<div id="chat-widget">
    <div id="chat-button" class="chat-button">
        <i class="fas fa-comments"></i>
        <span>Tư vấn</span>
    </div>
    <div id="chat-window" class="chat-window">
        <div class="chat-header">
            <h3>Tư vấn sản phẩm</h3>
            <button id="chat-close" class="chat-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="chat-body">
            <div id="chat-messages" class="chat-messages"></div>
            <div class="chat-input-container">
                <input type="text" id="chat-input" class="chat-input" placeholder="Nhập câu hỏi của bạn..." />
                <button id="chat-send" class="chat-send">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
#chat-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
}

.chat-button {
    background-color: #ff6b6b;
    color: white;
    padding: 15px 20px;
    border-radius: 50px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.chat-button:hover {
    background-color: #ff5252;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.chat-button i {
    font-size: 20px;
}

.chat-window {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 380px;
    height: 600px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    display: none;
    flex-direction: column;
    overflow: hidden;
    animation: slideUp 0.3s ease;
}

.chat-window.active {
    display: flex;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chat-header {
    background-color: #ff6b6b;
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.chat-close {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    padding: 5px;
    transition: transform 0.2s ease;
}

.chat-close:hover {
    transform: rotate(90deg);
}

.chat-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
    background: #f5f5f5;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.chat-messages::-webkit-scrollbar {
    width: 6px;
}

.chat-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 3px;
}

.chat-messages::-webkit-scrollbar-thumb:hover {
    background: #999;
}

.message {
    max-width: 80%;
    padding: 10px 12px;
    border-radius: 12px;
    word-wrap: break-word;
}

.message.user {
    align-self: flex-end;
    background: #ff6b6b;
    color: white;
}

.message.bot {
    align-self: flex-start;
    background: white;
    color: #333;
    border: 1px solid #e0e0e0;
}

.chat-input-container {
    display: flex;
    padding: 10px;
    background: white;
    border-top: 1px solid #e0e0e0;
    gap: 8px;
}

.chat-input {
    flex: 1;
    padding: 10px 15px;
    border: 1px solid #e0e0e0;
    border-radius: 20px;
    outline: none;
    font-size: 14px;
}

.chat-input:focus {
    border-color: #ff6b6b;
}

.chat-send {
    padding: 10px 20px;
    background: #ff6b6b;
    color: white;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    transition: background 0.3s;
}

.chat-send:hover {
    background: #ff5252;
}

.chat-send:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.loading {
    display: inline-block;
    width: 12px;
    height: 12px;
    border: 2px solid #ccc;
    border-top-color: #ff6b6b;
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

@media (max-width: 768px) {
    .chat-window {
        width: calc(100vw - 40px);
        height: calc(100vh - 120px);
        bottom: 80px;
        right: 20px;
        left: 20px;
    }
    
    #chat-widget {
        bottom: 15px;
        right: 15px;
    }
    
    .chat-button {
        padding: 12px 16px;
        font-size: 14px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatButton = document.getElementById('chat-button');
    const chatWindow = document.getElementById('chat-window');
    const chatClose = document.getElementById('chat-close');
    const chatInput = document.getElementById('chat-input');
    const chatSend = document.getElementById('chat-send');
    const chatMessages = document.getElementById('chat-messages');
    const webhookBaseUrl = 'index.php';
    
    if (!chatButton || !chatWindow || !chatClose || !chatInput || !chatSend || !chatMessages) return;
    
    chatButton.addEventListener('click', function(e) {
        e.stopPropagation();
        chatWindow.classList.toggle('active');
    });
    
    chatClose.addEventListener('click', function(e) {
        e.stopPropagation();
        chatWindow.classList.remove('active');
    });
    
    chatWindow.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    document.addEventListener('click', function(event) {
        if (chatWindow.classList.contains('active')) {
            if (!event.target.closest('#chat-widget')) {
                chatWindow.classList.remove('active');
            }
        }
    });
    
    function addMessage(text, isUser) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message ' + (isUser ? 'user' : 'bot');
        messageDiv.innerHTML = escapeHtml(text);
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function addLoadingMessage() {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message bot';
        messageDiv.id = 'loading-message';
        messageDiv.innerHTML = '<span class="loading"></span> Đang tìm kiếm...';
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function removeLoadingMessage() {
        const loadingMsg = document.getElementById('loading-message');
        if (loadingMsg) {
            loadingMsg.remove();
        }
    }
    
    function renderProductResponse(data) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message bot';
        
        let html = '<div style="font-size: 13px; line-height: 1.4; color: #333;">';
        
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
        
        messageDiv.innerHTML = html;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function sendMessage() {
        const message = chatInput.value.trim();
        if (!message) return;
        
        addMessage(message, true);
        chatInput.value = '';
        chatSend.disabled = true;
        addLoadingMessage();
        
        const url = new URL(webhookBaseUrl, window.location.origin);
        url.searchParams.set('controller', 'chat');
        url.searchParams.set('action', 'api');
        url.searchParams.set('chatInput', message);
        
        console.log('Fetching URL:', url.toString());
        
        // Set timeout for fetch request (200 seconds)
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 200000); // 200 seconds
        
        fetch(url.toString(), { signal: controller.signal })
            .then(response => {
                clearTimeout(timeoutId);
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error('HTTP error: ' + response.status);
                    });
                }
                return response.json();
            })
            .then(result => {
                removeLoadingMessage();
                chatSend.disabled = false;
                
                if (result.success && result.data) {
                    const data = result.data;
                    console.log('Full result:', result);
                    console.log('Data:', data);
                    console.log('Has p:', !!data.p);
                    console.log('Has items:', !!data.items);
                    console.log('Items is array:', Array.isArray(data.items));
                    
                    if (data.p && Array.isArray(data.items)) {
                        console.log('Rendering product response...');
                        renderProductResponse(data);
                    } else if (data.message) {
                        if (data.message === 'Workflow was started') {
                            addMessage('Đang xử lý yêu cầu của bạn, vui lòng đợi...', false);
                        } else {
                            addMessage(data.message, false);
                        }
                    } else if (data.items && Array.isArray(data.items) && data.items.length > 0) {
                        console.log('Rendering product response (items only)...');
                        renderProductResponse(data);
                    } else if (data.p) {
                        addMessage(data.p, false);
                    } else {
                        console.log('No valid data to render');
                        addMessage('Xin lỗi, tôi không thể tìm thấy thông tin.', false);
                    }
                } else if (result.error) {
                    let errorMsg = 'Xin lỗi, có lỗi xảy ra.';
                    
                    if (result.message) {
                        errorMsg = result.message;
                        if (result.message.includes('not registered') || result.message.includes('webhook')) {
                            errorMsg = 'Webhook chưa được kích hoạt trong n8n. Vui lòng kiểm tra lại cấu hình.';
                        }
                    } else if (result.error === 'API error' && result.response) {
                        try {
                            const apiError = JSON.parse(result.response);
                            if (apiError.message) {
                                errorMsg = apiError.message;
                                if (apiError.hint) {
                                    errorMsg += ' (' + apiError.hint + ')';
                                }
                            }
                        } catch (e) {
                            errorMsg = 'Lỗi kết nối đến n8n API';
                        }
                    }
                    
                    addMessage(errorMsg, false);
                } else {
                    addMessage('Xin lỗi, tôi không thể tìm thấy thông tin.', false);
                }
            })
            .catch(error => {
                clearTimeout(timeoutId);
                removeLoadingMessage();
                chatSend.disabled = false;
                console.error('Error:', error);
                
                let errorMsg = 'Xin lỗi, có lỗi xảy ra.';
                if (error.name === 'AbortError') {
                    errorMsg = 'Yêu cầu đã quá thời gian chờ (timeout 200s). Vui lòng thử lại.';
                } else if (error.message) {
                    if (error.message.includes('404')) {
                        errorMsg = 'Webhook chưa được kích hoạt. Vui lòng kiểm tra lại cấu hình n8n.';
                    } else if (error.message.includes('NetworkError') || error.message.includes('Failed to fetch')) {
                        errorMsg = 'Không thể kết nối đến server. Vui lòng kiểm tra kết nối mạng.';
                    } else {
                        errorMsg = 'Lỗi: ' + error.message;
                    }
                }
                addMessage(errorMsg, false);
            });
    }
    
    chatSend.addEventListener('click', sendMessage);
    
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
</script>

<?php require_once __DIR__ . '/../components/footer.php'; ?>

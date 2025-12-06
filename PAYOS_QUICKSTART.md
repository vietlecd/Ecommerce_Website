# PayOS Quick Start Guide

## What is PayOS?

PayOS is a Vietnamese payment gateway that allows customers to pay using:
- **QR Code**: Scan with any banking app
- **Bank Transfer**: Manual transfer with provided details
- **Direct Link**: One-click payment through PayOS checkout page

## For Developers

### Setup Steps

1. **Get PayOS Credentials**
   - Visit https://payos.vn
   - Register for a merchant account
   - Get your credentials from the dashboard:
     - Client ID
     - API Key
     - Checksum Key

2. **Configure Environment**
   
   Edit `config/environment.php` and add your credentials:
   ```php
   'PAYOS_CLIENT_ID' => 'your_client_id_here',
   'PAYOS_API_KEY' => 'your_api_key_here',
   'PAYOS_CHECKSUM_KEY' => 'your_checksum_key_here',
   'PAYOS_SANDBOX' => true  // Use true for testing
   ```

3. **Test the Integration**
   
   - Add items to cart
   - Proceed to checkout
   - Select "PayOS (QR Code)" payment method
   - Complete the form and place order
   - You'll be redirected to the payment page

### File Structure

```
config/
  └── payos.php              # PayOS configuration
models/
  └── PayosModel.php         # PayOS API integration
controllers/
  └── CheckoutController.php # Updated with PayOS handlers
views/pages/
  ├── checkout.php          # Updated with PayOS option
  ├── payment.php           # Payment page with QR code
  ├── payment_success.php   # Success page
  └── payment_failed.php    # Failure page
```

### API Endpoints

| Endpoint | Description |
|----------|-------------|
| `/index.php?controller=checkout&action=payment` | Display payment page |
| `/index.php?controller=checkout&action=paymentReturn` | Handle successful payment |
| `/index.php?controller=checkout&action=paymentCancel` | Handle cancelled payment |
| `/index.php?controller=checkout&action=paymentWebhook` | Receive payment notifications |
| `/index.php?controller=checkout&action=checkPaymentStatus` | Check payment status (AJAX) |

## For Users

### How to Pay with PayOS

1. **Add items to cart** and proceed to checkout
2. **Fill in shipping information**
3. **Select "PayOS (QR Code)"** as payment method
4. **Click "Place Order"**
5. You'll see the payment page with two options:

   **Option A: Scan QR Code**
   - Open your banking app
   - Select "Scan QR Code" or "Transfer"
   - Scan the displayed QR code
   - Confirm the payment in your app

   **Option B: Manual Transfer**
   - Use the displayed bank information:
     - Bank name
     - Account number
     - Amount
     - Transfer content (important!)
   - Make the transfer through your banking app or website
   - Use the exact transfer content shown

6. **Wait for confirmation**
   - The page automatically checks payment status
   - You'll be redirected to success page once payment is confirmed
   - You'll receive a confirmation email

### Important Notes

- ⏱️ Payment link expires in **15 minutes**
- 💬 Always use the **exact transfer content** shown
- ✅ Payment is confirmed automatically
- 📧 Check your email for order confirmation

## Troubleshooting

### Common Issues

**Q: QR code doesn't appear**
- Ensure PayOS credentials are configured correctly
- Check that you're using sandbox credentials for testing

**Q: Payment not confirmed**
- Verify you used the correct transfer content
- Check that payment was completed within 15 minutes
- Contact support if issue persists

**Q: Order status not updating**
- Webhook URL must be accessible from PayOS servers
- Check server logs for webhook errors

### Support

- PayOS Documentation: https://payos.vn/docs
- PayOS Support: support@payos.vn
- Project Issues: [GitHub Issues](https://github.com/vietlecd/Ecommerce_Website/issues)

## Security

- ✅ All payments are verified with HMAC-SHA256 signatures
- ✅ Webhooks are authenticated
- ✅ User inputs are sanitized
- ✅ HTTPS recommended for production

## Testing Checklist

- [ ] Sandbox credentials configured
- [ ] Can access payment page
- [ ] QR code displays correctly
- [ ] Payment information shows correctly
- [ ] Can complete test payment
- [ ] Order status updates after payment
- [ ] Success/failure pages work
- [ ] Webhook receives notifications

---

**Version**: 1.0
**Last Updated**: December 2025

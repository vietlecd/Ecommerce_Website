# PayOS Payment Integration

This document describes the PayOS payment gateway integration for the e-commerce website.

## Overview

PayOS is a Vietnamese payment gateway that allows customers to pay via QR code scanning or bank transfer. This integration adds PayOS as a payment method option during checkout.

## Features

- **QR Code Payment**: Customers can scan a QR code with their banking app to complete payment
- **Bank Transfer**: Manual transfer using provided bank account information
- **Payment Link**: Direct checkout URL for seamless payment experience
- **Webhook Support**: Automatic order status updates when payment is confirmed
- **Payment Verification**: Secure signature verification for all transactions

## Configuration

### Environment Variables

Set the following environment variables or update `config/payos.php`:

```php
PAYOS_CLIENT_ID=your_client_id
PAYOS_API_KEY=your_api_key
PAYOS_CHECKSUM_KEY=your_checksum_key
PAYOS_SANDBOX=true  // Set to false for production
```

### Sandbox Credentials

For testing, you can obtain sandbox credentials from PayOS:
1. Visit [PayOS Developer Portal](https://payos.vn)
2. Register for a developer account
3. Create a sandbox application
4. Copy the Client ID, API Key, and Checksum Key

### URLs Configuration

The following URLs are configured in `config/payos.php`:

- **Return URL**: `/index.php?controller=checkout&action=paymentReturn` - Where users are redirected after payment
- **Cancel URL**: `/index.php?controller=checkout&action=paymentCancel` - Where users are redirected if they cancel
- **Webhook URL**: `/index.php?controller=checkout&action=paymentWebhook` - Endpoint for payment notifications

## Usage

### Customer Flow

1. Customer adds items to cart
2. Customer proceeds to checkout
3. Customer fills in shipping information
4. Customer selects "PayOS (QR Code)" as payment method
5. Customer clicks "Place Order"
6. Customer is redirected to payment page showing:
   - QR code for scanning
   - Bank transfer information
   - Direct payment link
7. Customer completes payment
8. System receives webhook notification
9. Order status is updated to "Processing"
10. Customer is redirected to success page

### Payment Methods

Three payment methods are available:
- **Card**: Traditional card payment (existing)
- **Cash on Delivery**: Pay when receiving the order (existing)
- **PayOS (QR Code)**: Pay via QR code or bank transfer (new)

## Files Created/Modified

### New Files
- `models/PayosModel.php` - PayOS API integration
- `config/payos.php` - PayOS configuration
- `views/pages/payment.php` - Payment page with QR code
- `views/pages/payment_success.php` - Payment success page
- `views/pages/payment_failed.php` - Payment failed page

### Modified Files
- `controllers/CheckoutController.php` - Added PayOS payment handling
- `views/pages/checkout.php` - Added PayOS payment option
- `config/environment.php` - Added PayOS environment variables

## API Endpoints

### CheckoutController Actions

- `index` - Checkout page
- `payment` - Display payment QR code and information
- `paymentReturn` - Handle return from PayOS
- `paymentCancel` - Handle cancelled payment
- `paymentWebhook` - Receive payment notifications
- `success` - Payment success page
- `failed` - Payment failed page

## Security

- **Signature Verification**: All webhooks are verified using HMAC-SHA256
- **HTTPS Required**: Production environment should use HTTPS
- **API Key Protection**: Never expose API keys in client-side code
- **Order Validation**: Payment amounts are verified before processing

## Testing

### Test Payment Flow

1. Set `PAYOS_SANDBOX=true` in configuration
2. Add items to cart and proceed to checkout
3. Select PayOS payment method
4. Use sandbox credentials to complete test payment
5. Verify order status updates correctly

### Test Cases

- [ ] Successful payment
- [ ] Cancelled payment
- [ ] Expired payment link
- [ ] Invalid signature (webhook)
- [ ] Network timeout
- [ ] Concurrent payment attempts

## Troubleshooting

### Common Issues

**Issue**: QR code not displaying
- **Solution**: Check API credentials are correct
- **Solution**: Verify API endpoint is accessible

**Issue**: Payment not updating order status
- **Solution**: Check webhook URL is accessible from PayOS servers
- **Solution**: Verify webhook signature validation

**Issue**: "Payment link not available" error
- **Solution**: Ensure PayOS API is responding
- **Solution**: Check error logs in `logs/errors.log`

### Debug Mode

Enable debug logging by checking `logs/errors.log` for PayOS-related messages.

## Production Checklist

Before going to production:

- [ ] Set `PAYOS_SANDBOX=false`
- [ ] Update to production API credentials
- [ ] Configure production webhook URL
- [ ] Enable HTTPS for all payment pages
- [ ] Test with real payment amounts
- [ ] Set up error monitoring
- [ ] Configure payment notifications
- [ ] Test webhook signature verification

## Support

For PayOS API support:
- Documentation: [PayOS API Docs](https://payos.vn/docs)
- Support: support@payos.vn

## License

This integration is part of the e-commerce website project and follows the same license.

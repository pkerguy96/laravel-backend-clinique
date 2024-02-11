<table style="width: 100%;">
    <tr>
        <td style="padding: 16px;">
            <div style="width: 500px; max-width: 100%; margin: auto;">
                <p style="color: #1d1d1d; text-align: center; font-size: 16px; margin: 20px 0 30px 0;">
                    {{ __('Did you forget your password?') }}<br />
                    {{ __('No need to worry, we\'ve got you covered! Let us provide you with a new password') }}
                </p>
                <a href="{{ request()->getHost() .'/reset/'. $data['token'] }}" style="
						display: block;
						max-width: 100%;
						text-align: center;
						color: #fcfcfc;
						font-weight: 600;
						font-size: 18px;
						border-radius: 6px;
						background: #02c93b;
						width: max-content;
						padding: 12px 32px;
						text-decoration: unset;
						margin: auto;
					">
                    {{ __('Reset Password') }}
                </a>
            </div>
        </td>
    </tr>
</table>
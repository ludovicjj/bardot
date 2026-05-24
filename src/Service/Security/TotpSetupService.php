<?php

namespace App\Service\Security;

use App\Enum\BrandSetting;
use App\Service\Setting\BrandSettingService;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\SvgWriter;
use OTPHP\TOTP;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class TotpSetupService
{
    public function __construct(
        private BrandSettingService $brandSettingService,
        #[Autowire(env: 'BRAND_NAME')]
        private string $brandName,
    ) {
    }

    public function generateSecret(): string
    {
        return TOTP::generate()->getSecret();
    }

    public function buildOtpAuthUri(string $secret, string $accountLabel): string
    {
        $totp = TOTP::createFromSecret($secret);
        $totp->setLabel($accountLabel);
        $totp->setIssuer($this->brandSettingService->get(BrandSetting::SITE_NAME) ?? $this->brandName);

        return $totp->getProvisioningUri();
    }

    public function buildQrCodeSvg(string $uri, int $size = 280): string
    {
        $builder = new Builder(
            writer: new SvgWriter(),
            data: $uri,
            size: $size,
            margin: 0,
        );

        return $builder->build()->getString();
    }

    public function verifyCode(string $secret, string $code): bool
    {
        return TOTP::createFromSecret($secret)->verify($code);
    }
}

<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 */
final class Version20180610093112 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');

        $this->addSql(
            'CREATE TABLE language (
                      id UUID NOT NULL, 
                      iso VARCHAR(5) NOT NULL, 
                      system BOOLEAN NOT NULL DEFAULT false, 
                      PRIMARY KEY(id)
              )'
        );
        $this->addSql(
            'CREATE TABLE translation
                    (
                        translation_id UUID NOT NULL,
                        language VARCHAR(5) NOT NULL,
                        PRIMARY KEY(translation_id, language)
                    )'
        );

        foreach ($this->getLanguages() as $iso) {
            $this->addSql(
                'INSERT INTO language (id, iso) VALUES (?, ?)',
                [Uuid::uuid4()->toString(), $iso]
            );
        }

        $this->addSql('UPDATE language SET system = true WHERE iso in (\'en\', \'pl\')');

        $this->addSql('ALTER TABLE language rename column system to active');
    }

    /**
     * @return array
     */
    private function getLanguages(): array
    {
        return [
            'af' => 'af',
            'af_ZA' => 'af_ZA',
            'ar' => 'ar',
            'ar_AE' => 'ar_AE',
            'ar_BH' => 'ar_BH',
            'ar_DZ' => 'ar_DZ',
            'ar_EG' => 'ar_EG',
            'ar_IQ' => 'ar_IQ',
            'ar_JO' => 'ar_JO',
            'ar_KW' => 'ar_KW',
            'ar_LB' => 'ar_LB',
            'ar_LY' => 'ar_LY',
            'ar_MA' => 'ar_MA',
            'ar_OM' => 'ar_OM',
            'ar_QA' => 'ar_QA',
            'ar_SA' => 'ar_SA',
            'ar_SY' => 'ar_SY',
            'ar_TN' => 'ar_TN',
            'ar_YE' => 'ar_YE',
            'az' => 'az',
            'az_AZ' => 'az_AZ',
            'be' => 'be',
            'be_BY' => 'be_BY',
            'bg' => 'bg',
            'bg_BG' => 'bg_BG',
            'bs_BA' => 'bs_BA',
            'ca' => 'ca',
            'ca_ES' => 'ca_ES',
            'cs' => 'cs',
            'cs_CZ' => 'cs_CZ',
            'cy' => 'cy',
            'cy_GB' => 'cy_GB',
            'da' => 'da',
            'da_DK' => 'da_DK',
            'de' => 'de',
            'de_AT' => 'de_AT',
            'de_CH' => 'de_CH',
            'de_DE' => 'de_DE',
            'de_LI' => 'de_LI',
            'de_LU' => 'de_LU',
            'dv' => 'dv',
            'dv_MV' => 'dv_MV',
            'el' => 'el',
            'el_GR' => 'el_GR',
            'en' => 'en',
            'en_AU' => 'en_AU',
            'en_BZ' => 'en_BZ',
            'en_CA' => 'en_CA',
            'en_CB' => 'en_CB',
            'en_GB' => 'en_GB',
            'en_GH' => 'en_GH',
            'en_IE' => 'en_IE',
            'en_IL' => 'en_IL',
            'en_JM' => 'en_JM',
            'en_NG' => 'en_NG',
            'en_NZ' => 'en_NZ',
            'en_PH' => 'en_PH',
            'en_TT' => 'en_TT',
            'en_US' => 'en_US',
            'en_ZA' => 'en_ZA',
            'en_ZW' => 'en_ZW',
            'eo' => 'eo',
            'es' => 'es',
            'es_AR' => 'es_AR',
            'es_BO' => 'es_BO',
            'es_CL' => 'es_CL',
            'es_CO' => 'es_CO',
            'es_CR' => 'es_CR',
            'es_DO' => 'es_DO',
            'es_EC' => 'es_EC',
            'es_ES' => 'es_ES',
            'es_GT' => 'es_GT',
            'es_HN' => 'es_HN',
            'es_MX' => 'es_MX',
            'es_NI' => 'es_NI',
            'es_PA' => 'es_PA',
            'es_PE' => 'es_PE',
            'es_PR' => 'es_PR',
            'es_PY' => 'es_PY',
            'es_SV' => 'es_SV',
            'es_UY' => 'es_UY',
            'es_VE' => 'es_VE',
            'et' => 'et',
            'et_EE' => 'et_EE',
            'eu' => 'eu',
            'eu_ES' => 'eu_ES',
            'fa' => 'fa',
            'fa_IR' => 'fa_IR',
            'fi' => 'fi',
            'fi_FI' => 'fi_FI',
            'fo' => 'fo',
            'fo_FO' => 'fo_FO',
            'fr' => 'fr',
            'fr_BE' => 'fr_BE',
            'fr_CA' => 'fr_CA',
            'fr_CH' => 'fr_CH',
            'fr_FR' => 'fr_FR',
            'fr_LU' => 'fr_LU',
            'fr_MA' => 'fr_MA',
            'fr_MC' => 'fr_MC',
            'gl' => 'gl',
            'gl_ES' => 'gl_ES',
            'ga_GB' => 'ga_GB',
            'gd_GB' => 'gd_GB',
            'gu' => 'gu',
            'gu_IN' => 'gu_IN',
            'he' => 'he',
            'he_IL' => 'he_IL',
            'hi' => 'hi',
            'hi_IN' => 'hi_IN',
            'hr' => 'hr',
            'hr_BA' => 'hr_BA',
            'hr_HR' => 'hr_HR',
            'hu' => 'hu',
            'hu_HU' => 'hu_HU',
            'hy' => 'hy',
            'hy_AM' => 'hy_AM',
            'id' => 'id',
            'id_ID' => 'id_ID',
            'is' => 'is',
            'is_IS' => 'is_IS',
            'it' => 'it',
            'it_CH' => 'it_CH',
            'it_IT' => 'it_IT',
            'ja' => 'ja',
            'ja_JP' => 'ja_JP',
            'ka' => 'ka',
            'ka_GE' => 'ka_GE',
            'kk' => 'kk',
            'kk_KZ' => 'kk_KZ',
            'kn' => 'kn',
            'kn_IN' => 'kn_IN',
            'ko' => 'ko',
            'ko_KR' => 'ko_KR',
            'ky' => 'ky',
            'ky_KG' => 'ky_KG',
            'lt' => 'lt',
            'lt_LT' => 'lt_LT',
            'lv' => 'lv',
            'lv_LV' => 'lv_LV',
            'mi' => 'mi',
            'mi_NZ' => 'mi_NZ',
            'mk' => 'mk',
            'mk_MK' => 'mk_MK',
            'mn' => 'mn',
            'mn_MN' => 'mn_MN',
            'mr' => 'mr',
            'mr_IN' => 'mr_IN',
            'ms' => 'ms',
            'ms_BN' => 'ms_BN',
            'ms_MY' => 'ms_MY',
            'mt' => 'mt',
            'mt_MT' => 'mt_MT',
            'nb' => 'nb',
            'nb_NO' => 'nb_NO',
            'nl' => 'nl',
            'nl_BE' => 'nl_BE',
            'nl_NL' => 'nl_NL',
            'nn_NO' => 'nn_NO',
            'ns' => 'ns',
            'ns_ZA' => 'ns_ZA',
            'pa' => 'pa',
            'pa_IN' => 'pa_IN',
            'pl' => 'pl',
            'pl_PL' => 'pl_PL',
            'ps' => 'ps',
            'ps_AR' => 'ps_AR',
            'pt' => 'pt',
            'pt_AO' => 'pt_AO',
            'pt_BR' => 'pt_BR',
            'pt_PT' => 'pt_PT',
            'qu' => 'qu',
            'qu_BO' => 'qu_BO',
            'qu_EC' => 'qu_EC',
            'qu_PE' => 'qu_PE',
            'ro' => 'ro',
            'ro_RO' => 'ro_RO',
            'ru' => 'ru',
            'ru_RU' => 'ru_RU',
            'sa' => 'sa',
            'sa_IN' => 'sa_IN',
            'se' => 'se',
            'se_FI' => 'se_FI',
            'se_NO' => 'se_NO',
            'se_SE' => 'se_SE',
            'sk' => 'sk',
            'sk_SK' => 'sk_SK',
            'sl' => 'sl',
            'sl_SI' => 'sl_SI',
            'sq' => 'sq',
            'sq_AL' => 'sq_AL',
            'sr' => 'sr',
            'sr_BA' => 'sr_BA',
            'sr_SP' => 'sr_SP',
            'sv' => 'sv',
            'sv_FI' => 'sv_FI',
            'sv_SE' => 'sv_SE',
            'sw' => 'sw',
            'sw_KE' => 'sw_KE',
            'ta' => 'ta',
            'ta_IN' => 'ta_IN',
            'te' => 'te',
            'te_IN' => 'te_IN',
            'th' => 'th',
            'th_TH' => 'th_TH',
            'tl' => 'tl',
            'tl_PH' => 'tl_PH',
            'tn' => 'tn',
            'tn_ZA' => 'tn_ZA',
            'tr' => 'tr',
            'tr_TR' => 'tr_TR',
            'tt' => 'tt',
            'tt_RU' => 'tt_RU',
            'ts' => 'ts',
            'uk' => 'uk',
            'uk_UA' => 'uk_UA',
            'ur' => 'ur',
            'ur_PK' => 'ur_PK',
            'uz' => 'uz',
            'uz_UZ' => 'uz_UZ',
            'vi' => 'vi',
            'vi_VN' => 'vi_VN',
            'xh' => 'xh',
            'xh_ZA' => 'xh_ZA',
            'zh' => 'zh',
            'zh_CN' => 'zh_CN',
            'zh_HK' => 'zh_HK',
            'zh_MO' => 'zh_MO',
            'zh_SG' => 'zh_SG',
            'zh_TW' => 'zh_TW',
            'zu' => 'zu',
            'zu_ZA' => 'zu_ZA',
        ];
    }
}

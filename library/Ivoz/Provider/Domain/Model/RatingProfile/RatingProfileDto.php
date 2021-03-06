<?php

namespace Ivoz\Provider\Domain\Model\RatingProfile;

class RatingProfileDto extends RatingProfileDtoAbstract
{
    /**
     * @inheritdoc
     */
    public static function getPropertyMap(string $context = '', string $role = null)
    {
        if ($context === self::CONTEXT_COLLECTION) {
            $response = [
                'activationTime' => 'activationTime',
                'id' => 'id',
                'companyId' => 'company',
                'carrierId' => 'carrier',
                'ratingPlanGroupId' => 'ratingPlanGroup',
                'routingTagId' => 'routingTag'
            ];
        } else {
            $response = parent::getPropertyMap(...func_get_args());
        }

        if ($role === 'ROLE_COMPANY_ADMIN') {
            return self::filterFieldsForCompanyAdmin($response);
        }

        return $response;
    }

    /**
     * @param array $response
     * @return array
     */
    private static function filterFieldsForCompanyAdmin(array $response): array
    {
        $allowedFields = [
            'activationTime',
            'id',
            'companyId',
            'ratingPlanGroupId',
            'routingTagId'
        ];

        $response = array_filter(
            $response,
            function ($key) use ($allowedFields) {
                return in_array($key, $allowedFields, true);
            },
            ARRAY_FILTER_USE_KEY
        );

        return $response;
    }
}

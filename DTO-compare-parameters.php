<?php

// Выделение общих параметров в DTO

// TaskController::getAjax()
// метод для получения задач по AJAx
public function actionGetAjax($q = null, $id = null): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $array = Task::find()->select(['id'])
                ->where(['id' => $q])->limit(10)->all();
            $out['results'] = ArrayHelper::getColumn(
                $array,
                static function ($model) {
                    return [
                        'id' => $model->id,
                        'text' => $model->id
                    ];
                }
            );
        } elseif ($id > 0) {
            $out['results'] = [
                'id' => $id,
                'text' => Task::findOne($id)->id
            ];
        }

        return $out;
    }

// HelpDeskController::getAjax()
//  метод для получения заявок СП по AJAx
public function actionGetAjax(?string $q = null, null|int|string $id = null): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON; //restituisco json
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $array = HelpDesk::find()->select(['id'])
                ->where(['id' => $q])->limit(10)->all();
            $out['results'] = ArrayHelper::getColumn(
                $array,
                static function ($model) {
                    return [
                        'id' => $model->id,
                        'text' => $model->id
                    ];
                }
            );
        } elseif ($id > 0) {
            $out['results'] = [
                'id' => $id,
                'text' => HelpDesk::findOne($id)->id
            ];
        }

        return $out;
    }

// EmployeeController::getAjax()
// метод для получения сотрудников по AJAx
public function actionGetAjax($q = '', $id = null, $t = '1', $company = null): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON; //restituisco json
        $out = ['results' => []];
        $q = trim($q);

        $company_id = $company;
        if (
            !empty($company_id)
            && empty(LinkUserCityAccess::findOne(['company_id' => $company_id, 'user_id' => Yii::$app->user->id]))
            && empty(Employee::findOne(['company_id' => $company_id, 'user_id' => Yii::$app->user->id]))
        ) {
            throw new ForbiddenHttpException(Yii::t('app', 'Нет доступа к данной компании'));
        }
        // ... много кода
    }

class AjaxDto {
  public string $q;
  public int $id;
  public ?string $t;
}

// TaskController::getAjax()
public function actionGetAjax(AjaxDto $dto): array
// HelpDeskController::getAjax()
public function actionGetAjax(AjaxDto $dto): array
// EmployeeController::getAjax()
public function actionGetAjax(AjaxDto $dto, ?Company $company = null): array

/**
     * Аякс для обновление имени сотрудника в новой view оператора/продюсер
     *
     * @param $first_name string
     * @param $id         int
     *
     * @return array
     *
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionAjaxUpdateFirstName(string $first_name, int $id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->user->identity->hasNoAccessChain(['producer'])) {
            $model = $this->findModelCommon($id);
            $model->first_name = $first_name;
            if ($model->save()) {
                return ['success' => true];
            }
        }
        return ['success' => false, 'message' => Yii::t('app', 'Ошибка')];
    }

    /**
     * Аякс для обновление фамилии сотрудника в новой view оператора/продюсер
     *
     * @param $last_name string
     * @param $id        int
     *
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     *
     * @return array
     */
    public function actionAjaxUpdateLastName(string $last_name, int $id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->user->identity->hasNoAccessChain(['producer'])) {
            $model = $this->findModelCommon($id);
            $model->last_name = $last_name;
            if ($model->save()) {
                return ['success' => true];
            }
        }
        return ['success' => false, 'message' => Yii::t('app', 'Ошибка')];
    }

    /**
     * Аякс для обновление отчества сотрудника в новой view оператора/продюсер
     *
     * @param $middle_name string
     * @param $id          int
     *
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     *
     * @return array
     */
    public function actionAjaxUpdateMiddleName(string $middle_name, int $id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->user->identity->hasNoAccessChain(['producer'])) {
            $model = $this->findModelCommon($id);
            $model->middle_name = $middle_name;
            if ($model->save()) {
                return ['success' => true];
            }
        }
        return ['success' => false, 'message' => Yii::t('app', 'Ошибка')];
    }

// комбинированное решение

// однаком в данном случае может подойти (даже лучше) и VO
class FullNameDto {
  public string $first_name;
  public string $middle_name;
  public string $last_name;
}

public function actionAjaxChangeFullname(FullNameDto $dto): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->user->identity->hasNoAccessChain(['producer'])) {
            // изменение ФИО по частям или целиком
            if ($model->save()) {
                return ['success' => true];
            }
        }
        return ['success' => false, 'message' => Yii::t('app', 'Ошибка')];
    }

// 

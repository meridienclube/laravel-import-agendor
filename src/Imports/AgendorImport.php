<?php

namespace MeridienClube\Meridien\Imports;

use MeridienClube\Meridien\Import;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AgendorImport implements ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    use Importable;

    public $import;
    private $rows = 0;

    public function __construct(Import $import)
    {
        $this->import = $import;
    }

    public function model(array $row)
    {

        if (empty($row['pessoa_relacionada'])) {
            return null;
        }

        //dd($row);

        $task_type_id = $this->import->settings['task_type_id'];
        $task_status_id = $this->import->settings['task_status_id'];
        $task_type = resolve('TaskTypeService')->find($task_type_id);
        //$task_status_closed_id = isset($task_type->closed_status_id)? : $task_status_id;
        $task_status_closed_id = $task_type->closed_status_id;

        $user_id = $this->import->settings['user_id'];
        $step_id = $this->import->settings['step_id'];
        $role_id = $this->import->settings['role_id'];
        $status_id = isset($this->import->settings['status_id']) ? $this->import->settings['status_id'] : 1;

        $codigodoassociadoindicador = $row['codigo_do_associado_indicador'];
        $codigo_intranet = isset($codigodoassociadoindicador) && $codigodoassociadoindicador > 0 ? $codigodoassociadoindicador : NULL;
        $indicator = isset($codigo_intranet) ? resolve('UserService')->findBy('option.codigo_intranet', $codigo_intranet) : NULL;

        //dd($indicator);

        //if(isset($row['codigo_do_associado_indicador'])){
            //dd($row['codigo_do_associado_indicador']);
        //}

        //if($codigodoassociadoindicador){
        //    dd($codigodoassociadoindicador);
        //}

        ++$this->rows;

        $user = resolve('UserService')->findBy('contact.content', $row['celular']);
        if (!$user) {
            $user = resolve('UserService')->create([
                'status_id' => $status_id,
                'name' => $row['pessoa_relacionada'],
                //'email' => $row['e-mail'],
                'syncWithoutDetaching' => [
                    'indicator' => isset($indicator) ? $indicator->id : NULL,
                    'roles' => [
                        isset($role_id) ? $role_id : NULL
                    ],
                    'steps' => [
                        isset($step_id) ? $step_id : NULL
                    ]
                ],
                'sync' => [
                    'address' => [
                        "country" => $row['pais'],
                        "state" => $row['estado'],
                        "city" => $row['cidade'],
                        "cep" => $row['cep'],
                        "neighborhood" => $row['bairro'],
                        "street" => $row['rua'],
                        "number" => $row['numero'],
                        "complement" => $row['complemento']
                    ],
                ],
                'attach' => [
                    //'indicator' => isset($indicator) ? $indicator->id : NULL,
                    'base' => $user_id,
                    'contacts' => [
                        'email' => $row['e_mail'],
                        'phone' => $row['telefone'],
                        'cellphone' => $row['celular'],
                    ],
                    'social_networks' => [
                        "Facebook" => $row['facebook'],
                        "Twitter" => $row['twitter'],
                        "LinkedIn" => $row['linkedin'],
                        "Skype" => $row['skype'],
                        "Instagram" => $row['instagram']
                    ]
                ]
            ]);
        }
        $task = resolve('TaskService')->create([
            'type_id' => $task_type_id,
            'sync' => [
                'destinateds' => [$user->id],
                'responsibles' => [$user_id]
            ],
            'user_id' => $user_id,
            'datetime' => Date::excelToDateTimeObject($row['data_de_agendamento']),
            //'status_id' => $task_status_id,
            'status_id' => (empty($row['data_de_finalizacao']))? $task_status_id : $task_status_closed_id,
            'priority' => 1
        ]);

        resolve('TaskService')->createComment(['content' => $row['comentario'], 'user_id' => $user_id], $task->id);

    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}

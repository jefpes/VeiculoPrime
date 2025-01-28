<?php

namespace Database\Seeders;

use App\Models\Extra;
use Illuminate\Database\Seeder;

class ExtraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Leilão',
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m12 14.006l-6.156 7.227a2.182 2.182 0 1 1-3.077-3.077L9.994 12M22 11.905L15.905 18m-3.81-16L6 8.095m5.333-5.333L6.762 7.333s2.286 3.048 4.571 5.334c2.286 2.285 5.334 4.571 5.334 4.571l4.571-4.571s-2.286-3.048-4.571-5.334c-2.286-2.285-5.334-4.571-5.334-4.571" color="currentColor"/></svg>',
            ],
            [
                'name' => 'Chave reserva',
                'icon' => '<svg width="24" height="24" viewBox="0 0 16 16"><g fill="currentColor"><path d="M11 6a1 1 0 1 0 0-2a1 1 0 0 0 0 2"/><path d="M7.5 12v-.5h1A.5.5 0 0 0 9 11v-1h1a4 4 0 1 0-3.838-2.87L2.292 11a1 1 0 0 0-.292.707V13a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-.5h1a.5.5 0 0 0 .5-.5M7 6a3 3 0 1 1 3 3H8.5a.5.5 0 0 0-.5.5v1H7a.5.5 0 0 0-.5.5v.5h-1a.5.5 0 0 0-.5.5v1H3v-1.293l4.089-4.089a.5.5 0 0 0 .113-.534C7.072 6.748 7 6.384 7 6"/></g></svg>',
            ],
            [
                'name' => 'Garantia de fábrica',
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24"><g fill="none" fill-rule="evenodd"><path d="m12.594 23.258l-.012.002l-.071.035l-.02.004l-.014-.004l-.071-.036q-.016-.004-.024.006l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.016-.018m.264-.113l-.014.002l-.184.093l-.01.01l-.003.011l.018.43l.005.012l.008.008l.201.092q.019.005.029-.008l.004-.014l-.034-.614q-.005-.019-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.003-.011l.018-.43l-.003-.012l-.01-.01z"/><path fill="currentColor" d="M9 3a1 1 0 0 1 .993.883L10 4l.002.562l.019 1.185l.045 1.478l.068 1.567l.018.35l3.334-2a1 1 0 0 1 1.507.74L15 8v2.132l4.445-2.964a1 1 0 0 1 1.548.71L21 8v12a1 1 0 0 1-.883.993L20 21H4a1 1 0 0 1-.995-1.1l.096-.976l.173-1.856l.183-2.137l.123-1.566l.078-1.091l.126-1.974l.091-1.7l.076-1.85l.026-.92L4 4.34v-.337a1 1 0 0 1 .883-.997L5 3zm4 6.766l-2.717 1.63l.089 1.299l.105 1.408l.123 1.52l.143 1.631l.163 1.746H19V9.869l-4.445 2.963A1 1 0 0 1 13 12zM8.007 5H5.993l-.035 1.54l-.062 1.65l-.087 1.74l-.058.986l-.094 1.435l-.18 2.416l-.15 1.79l-.176 1.936l-.048.507h3.794l-.14-1.493l-.124-1.41l-.11-1.33l-.18-2.416l-.094-1.435l-.106-1.894l-.05-1.092l-.057-1.594l-.022-.894zM13 14a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0v-1a1 1 0 0 1 1-1m4 0a1 1 0 0 1 .993.883L18 15v1a1 1 0 0 1-1.993.117L16 16v-1a1 1 0 0 1 1-1"/></g></svg>',
            ],
            [
                'name' => 'Manual',
                'icon' => '<svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>',
            ],
            [
                'name' => 'Multas',
                'icon' => '<svg width="24" height="24" viewBox="0 0 32 32"><path fill="currentColor" d="M10 24h2v6h-2z"/><path fill="currentColor" d="M21 20H7a3.003 3.003 0 0 1-3-3V6H2v11a5.006 5.006 0 0 0 5 5h14a4.9 4.9 0 0 1 2.105.481L17 28.586L18.414 30l6.307-6.307A4.96 4.96 0 0 1 26 27v3h2v-3a7.01 7.01 0 0 0-7-7"/><path fill="currentColor" d="m25.275 4.039l-7-2a1 1 0 0 0-.55 0l-7 2a1 1 0 0 0-.695 1.203L11 9.123V11a7 7 0 1 0 14 0V9.123l.97-3.88a1 1 0 0 0-.695-1.204M18 4.04l5.795 1.656L23.22 8H19V6h-2v2h-4.219l-.576-2.304ZM18 16a5.006 5.006 0 0 1-5-5v-1h10v1a5.006 5.006 0 0 1-5 5"/></svg>',
            ],
            [
                'name' => 'IPVA pago',
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 5h-5m0 0H9.5a3.5 3.5 0 1 0 0 7H12m0-7V3m0 2v7m0 0h2.5a3.5 3.5 0 1 1 0 7H12m0-7v7m0 0H6m6 0v2"/></svg>',
            ],
            [
                'name' => 'Revisões feitas na concessionária',
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24"><g fill="none" fill-rule="evenodd"><path d="m12.594 23.258l-.012.002l-.071.035l-.02.004l-.014-.004l-.071-.036q-.016-.004-.024.006l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.016-.018m.264-.113l-.014.002l-.184.093l-.01.01l-.003.011l.018.43l.005.012l.008.008l.201.092q.019.005.029-.008l.004-.014l-.034-.614q-.005-.019-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.003-.011l.018-.43l-.003-.012l-.01-.01z"/><path fill="currentColor" d="M19.264 3.364a1 1 0 0 1 1.221.15l.007.008a1 1 0 0 1 .148 1.225l-1.068 1.761a1 1 0 0 1-1.113.447l-3.63 3.63l.413.415c.952.951.925 2.489-.009 3.423a2.38 2.38 0 0 1-1.68.698a.37.37 0 0 0-.33.205l-.89 1.78a3 3 0 0 1-.563.78l-3.306 3.306a3 3 0 0 1-4.242 0l-1.415-1.414a3 3 0 0 1 0-4.243l3.306-3.306a3 3 0 0 1 .78-.562l1.78-.89a.37.37 0 0 0 .205-.331c0-.633.257-1.238.698-1.68c.935-.934 2.473-.96 3.424-.009l.414.414l3.63-3.63a1 1 0 0 1 .452-1.116zm-6.558 7.928l-1.12-1.12a.426.426 0 0 0-.595.009a.38.38 0 0 0-.112.265a2.37 2.37 0 0 1-1.31 2.12l-1.781.89a1 1 0 0 0-.26.188L4.222 16.95a1 1 0 0 0 0 1.414l1.414 1.414a1 1 0 0 0 1.414 0l3.306-3.306a1 1 0 0 0 .187-.26l.89-1.78a2.37 2.37 0 0 1 2.12-1.31a.38.38 0 0 0 .266-.113a.426.426 0 0 0 .01-.595l-1.12-1.12zm-4.949 3.536a1 1 0 0 1 1.414 1.415l-2.121 2.12a1 1 0 1 1-1.414-1.413z"/></g></svg>',
            ],
            [
                'name' => 'Único dono',
                'icon' => '<svg width="24" height="24" viewBox="0 0 32 32"><path fill="currentColor" d="M18 30h-4a2 2 0 0 1-2-2v-7a2 2 0 0 1-2-2v-6a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3v6a2 2 0 0 1-2 2v7a2 2 0 0 1-2 2m-5-18a.94.94 0 0 0-1 1v6h2v9h4v-9h2v-6a.94.94 0 0 0-1-1zm3-3a4 4 0 1 1 4-4a4 4 0 0 1-4 4m0-6a2 2 0 1 0 2 2a2 2 0 0 0-2-2"/></svg>',
            ],
            [
                'name' => 'Veículo em financiamento',
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 5h-5m0 0H9.5a3.5 3.5 0 1 0 0 7H12m0-7V3m0 2v7m0 0h2.5a3.5 3.5 0 1 1 0 7H12m0-7v7m0 0H6m6 0v2"/></svg>',
            ],
            [
                'name' => 'Veículo quitado',
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 5h-5m0 0H9.5a3.5 3.5 0 1 0 0 7H12m0-7V3m0 2v7m0 0h2.5a3.5 3.5 0 1 1 0 7H12m0-7v7m0 0H6m6 0v2"/></svg>',
            ],
            [
                'name' => 'Apoio na documentação',
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 4a2 2 0 0 1 2-2h8a1 1 0 0 1 .707.293l5 5A1 1 0 0 1 20 8v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm13.586 4L14 4.414V8zM12 4H6v16h12V10h-5a1 1 0 0 1-1-1zm-4 9a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1m0 4a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1"/></svg>',
            ],
            [
                'name' => 'Entrega do veículo',
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="currentColor"><path d="M19.5 17.5a2.5 2.5 0 1 1-5 0a2.5 2.5 0 0 1 5 0m-10 0a2.5 2.5 0 1 1-5 0a2.5 2.5 0 0 1 5 0"/><path d="M14.5 17.5h-5m10 0h.763c.22 0 .33 0 .422-.012a1.5 1.5 0 0 0 1.303-1.302c.012-.093.012-.203.012-.423V13a6.5 6.5 0 0 0-6.5-6.5M2 4h10c1.414 0 2.121 0 2.56.44C15 4.878 15 5.585 15 7v8.5M2 12.75V15c0 .935 0 1.402.201 1.75a1.5 1.5 0 0 0 .549.549c.348.201.815.201 1.75.201M2 7h6m-6 3h4"/></g></svg>',
            ],
            [
                'name' => 'Garantia de motor',
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M8 10h8v8h-5l-2-2H7v-5m0-7v2h3v2H7l-2 2v3H3v-3H1v8h2v-3h2v3h3l2 2h8v-4h2v3h3V9h-3v3h-2V8h-6V6h3V4z"/></svg>',
            ],
            [
                'name' => 'Garantia de câmbio',
                'icon' => '<svg width="24" height="24" viewBox="0 0 48 48"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4"><path d="M40 12v12H8m16-12v24M8 12v24"/><path d="M44 8a4 4 0 1 1-8 0a4 4 0 0 1 8 0M28 8a4 4 0 1 1-8 0a4 4 0 0 1 8 0M12 8a4 4 0 1 1-8 0a4 4 0 0 1 8 0m16 32a4 4 0 1 1-8 0a4 4 0 0 1 8 0m-16 0a4 4 0 1 1-8 0a4 4 0 0 1 8 0m28 4a4 4 0 1 0 0-8a4 4 0 0 0 0 8"/></g></svg>',
            ],
            [
                'name' => 'Pneus novos',
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m19.66 9.64l-.36-.94l1.86-.7c-.92-2.12-2.56-3.82-4.62-4.86l-.8 1.78l-.92-.42l.8-1.8C14.5 2.26 13.28 2 12 2c-1.06 0-2.08.22-3.04.5l.68 1.84l-.94.36L8 2.84c-2.12.92-3.82 2.56-4.86 4.62l1.78.8l-.42.92l-1.8-.8C2.26 9.5 2 10.72 2 12c0 1.06.22 2.08.5 3.04l1.84-.68l.36.94l-1.86.7c.92 2.12 2.56 3.82 4.62 4.86l.8-1.78l.92.42l-.8 1.8c1.12.44 2.34.7 3.62.7c1.06 0 2.08-.22 3.04-.5l-.68-1.84l.94-.36l.7 1.86c2.12-.92 3.82-2.56 4.86-4.62l-1.78-.8l.42-.92l1.8.8c.44-1.12.7-2.34.7-3.62c0-1.06-.22-2.08-.5-3.04zm-5.36 7.9c-3.06 1.26-6.58-.18-7.84-3.24s.18-6.58 3.24-7.84s6.58.18 7.84 3.24a5.986 5.986 0 0 1-3.24 7.84"/></svg>',
            ],
            [
                'name' => 'Tanque cheio',
                'icon' => '<svg width="24" height="24" viewBox="0 0 40 40" fill="none"><g clip-path="url(#clip0_4759_54840)"><path d="M23.7502 18.1517H25.4169C26.301 18.1517 27.1488 18.4952 27.7739 19.1068C28.3991 19.7183 28.7502 20.5477 28.7502 21.4126V26.3039C28.7502 26.9525 29.0136 27.5746 29.4825 28.0332C29.9513 28.4918 30.5872 28.7495 31.2502 28.7495C31.9133 28.7495 32.5492 28.4918 33.018 28.0332C33.4868 27.5746 33.7502 26.9525 33.7502 26.3039V14.8908L28.7502 9.99951" stroke="black" stroke-width="2.51138" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.25024 33.7495V9.68701C6.25024 8.77533 6.61899 7.90099 7.27537 7.25633C7.93175 6.61168 8.82199 6.24951 9.75024 6.24951H20.2502C21.1785 6.24951 22.0687 6.61168 22.7251 7.25633C23.3815 7.90099 23.7502 8.77533 23.7502 9.68701V33.7495" stroke="black" stroke-width="2.51138" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.99976 33.7495H24.9998" stroke="black" stroke-width="2.51138" stroke-linecap="round" stroke-linejoin="round"/><path d="M30.0002 11.25V13.125C30.0002 13.6223 30.1978 14.0992 30.5494 14.4508C30.901 14.8025 31.378 15 31.8752 15H33.7502" stroke="#141414" stroke-width="2.51138" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.25024 22.0005H23.7502" stroke="#141414" stroke-width="2.51138" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.9235 17.7605C14.2353 17.7605 13.6439 17.6469 13.1495 17.4197C12.655 17.1859 12.2508 16.8818 11.9367 16.5077C11.6227 16.1335 11.3922 15.7259 11.2452 15.2849C11.0982 14.8439 11.0247 14.4129 11.0247 13.9919V13.7714C11.0247 13.3171 11.1015 12.8694 11.2552 12.4284C11.4089 11.9807 11.6394 11.5765 11.9467 11.2157C12.2608 10.8548 12.6517 10.5675 13.1194 10.3537C13.5871 10.1332 14.135 10.0229 14.7631 10.0229C15.438 10.0229 16.0327 10.1466 16.5472 10.3938C17.0684 10.641 17.486 10.9851 17.8 11.4261C18.1141 11.8604 18.2978 12.3649 18.3513 12.9396H16.487C16.447 12.7191 16.3501 12.5219 16.1964 12.3482C16.0427 12.1678 15.8422 12.0275 15.595 11.9273C15.3545 11.8204 15.0772 11.7669 14.7631 11.7669C14.4625 11.7669 14.1952 11.8204 13.9613 11.9273C13.7341 12.0275 13.5404 12.1745 13.38 12.3683C13.2263 12.5554 13.1094 12.7792 13.0292 13.0398C12.949 13.3004 12.9089 13.5844 12.9089 13.8917C12.9089 14.1991 12.9524 14.4864 13.0392 14.7537C13.1261 15.0143 13.253 15.2448 13.4201 15.4452C13.5871 15.639 13.7976 15.7894 14.0515 15.8963C14.3054 16.0032 14.5961 16.0566 14.9235 16.0566C15.3378 16.0566 15.6952 15.9698 15.9959 15.796C16.2966 15.6223 16.5071 15.3918 16.6274 15.1045L16.487 16.207V14.6134H18.1909V16.1168C17.8835 16.6446 17.4459 17.0522 16.8779 17.3395C16.3167 17.6202 15.6652 17.7605 14.9235 17.7605ZM14.723 14.9742V13.6712H18.7923V14.9742H14.723Z" fill="#141414"/></g><defs><clipPath id="clip0_4759_54840"><rect width="40" height="40" fill="white"/></clipPath></defs></svg>',
            ],
        ];

        foreach ($data as $d) {
            Extra::updateOrCreate(['name' => $d['name'], 'icon' => $d['icon']]);
        }
    }
}

interface Enum<T = number> {
    readonly label: string | null;
    readonly name: string;
    readonly value: T;
}

export interface Paginated<DataT = unknown> {
    data: DataT;

    links: {
        first: string;
        last: string;
        prev: string | null;
        next: string | null;
    };

    meta: {
        current_page: number;
        from: number;
        last_page: number;

        links: {
            url: string | null;
            label: string;
            page: number | null;
            active: boolean;
        }[];

        path: string;
        per_page: number;
        to: number;
        total: number;
    };
}

interface BaseModel {
    readonly id: number;
    readonly created_at: string;
    readonly updated_at: string;
}

export interface UserModel extends BaseModel {
    readonly name: string;
}

export interface UserResource extends BaseModel {
    readonly name: string;
}

export interface PoemTagResource extends BaseModel {
    readonly name: string;
}

export interface PoemResource extends BaseModel {
    readonly title: string | null;
    readonly author: string | null;
    readonly dynasty: string | null;
    readonly content: string;
    readonly source_type: Enum;
    readonly display_status: Enum;
    readonly deleted_at: string;

    readonly user: UserResource | null;
    readonly tags: PoemTagResource[] | null;
    readonly images: PoemImageResource[] | null;
}

export interface FileResource extends BaseModel {
    readonly download_url: string;
}

export interface PoemImageResource extends BaseModel {
    readonly poem: PoemResource | null;
    readonly file: FileResource | null;

    liked: boolean | null;
    likes_count: number | null;
}
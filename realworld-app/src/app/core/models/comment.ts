import { Author } from "./author";

export interface Comment {
    id: number;
    createdAt: string;
    updatedAt: string;
    body: string;
    author: Author;
}